<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:05
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\TeacherRepository;
use Katniss\Everdeen\Repositories\TopicRepository;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;

class TeacherController extends ViewController
{
    protected $teacherRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'teacher';
        $this->teacherRepository = new TeacherRepository();
    }

    #region Sign up
    public function getSignUp(Request $request)
    {
        if ($request->isAuth() && $request->authUser()->hasRole('teacher')) {
            return redirect(homeUrl());
        }

        return $this->_any('sign_up', [
            'skype_id' => 'skype_id',
            'skype_name' => 'Skype',
            'hot_line' => '1900 1000',
            'email' => 'example@example.com',
        ]);
    }

    public function postSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|max:255',
            'skype_id' => 'required|max:255',
            'phone_code' => 'required|in:' . implode(',', allCountryCodes()),
            'phone_number' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
        ]);;

        $errorRdr = redirect(homeUrl('teacher/sign-up'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $user = $this->teacherRepository->create(
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('skype_id'),
                $request->input('phone_code'),
                $request->input('phone_number')
            );

            if ($user) {
                Auth::guard()->login($user);
            } else {
                return $errorRdr->withErrors([trans('auth.register_failed_system_error')]);
            }
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 1]));
    }

    public function getSignUpStep(Request $request, $step)
    {
        if ($step == 1) {
            return $this->getSignUpStep1($request);
        } elseif ($step == 2) {
            return $this->getSignUpStep2($request);
        }

        abort(404);
        return false;
    }

    public function getSignUpStep1(Request $request)
    {
        return $this->_any('sign_up_step_1', [
            'teacher' => $request->authUser()->teacherProfile,
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    public function getSignUpStep2(Request $request)
    {
        $topicRepository = new TopicRepository();
        $teacher = $request->authUser()->teacherProfile;
        $teacherCertificates = $teacher->certificates;

        return $this->_any('sign_up_step_2', [
            'teacher' => $teacher,
            'topics' => $topicRepository->getAll(),
            'teacher_topic_ids' => $teacher->topics->pluck('id')->all(),
            'teacher_certificates' => $teacherCertificates,
            'teacher_other_certificates' => array_key_exists('others', $teacherCertificates) ? $teacherCertificates['others'] : '',
            'certificates' => _k('certificates'),
        ]);
    }

    public function postSignUpStep(Request $request, $step)
    {
        if ($step == 1) {
            return $this->postSignUpStep1($request);
        } elseif ($step == 2) {
            return $this->postSignUpStep2($request);
        }

        abort(404);
        return false;
    }

    public function postSignUpStep1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'sometimes|date_format:' . DateTimeHelper::shortDateFormat(),
            'gender' => 'required|in:' . implode(',', allGenders()),
            'city' => 'required|max:255',
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'nationality' => 'required|in:' . implode(',', allCountryCodes()),
            'facebook' => 'required|max:255|url',
        ]);;

        $errorRdr = redirect(homeUrl('teacher/sign-up/step/1'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $userRepository = new UserRepository($request->authUser());
        $settings = settings();

        try {
            $userRepository->updateAttributes([
                'date_of_birth' => DateTimeHelper::getInstance()
                    ->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true),
                'gender' => $request->input('gender'),
                'city' => $request->input('city'),
                'nationality' => $request->input('nationality'),
                'facebook' => $request->input('facebook'),
            ]);

            $settings->setCountry($request->input('country'));
            $settings->storeUser();
            $settings->storeSession();

        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return $settings->storeCookie(redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 2])));
    }

    public function postSignUpStep2(Request $request)
    {
        $certificates = _k('certificates');
        $validator = Validator::make($request->all(), [
            'topics' => 'required|array|exists:topics,id',
            'about_me' => 'required',
            'experience' => 'required',
            'methodology' => 'required',
            'certificates' => 'required|array|in:' . implode(',', $certificates),
            'video_introduce_url' => 'required|max:255|url',
        ]);

        $errorRdr = redirect(homeUrl('teacher/sign-up/step/{step}', ['step' => 2]))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        if (in_array('Others', $request->input('certificates'))) {
            $validator = Validator::make($request->all(), [
                'other_certificates' => 'required|max:200',
            ]);
            if ($validator->fails()) {
                return $errorRdr->withErrors($validator);
            }
        }

        $this->teacherRepository->model($request->authUser()->teacherProfile);

        try {
            $this->teacherRepository->updateWhenSigningUp(
                $request->input('topics'),
                $request->input('about_me'),
                $request->input('experience'),
                $request->input('methodology'),
                $request->input('certificates'),
                $request->input('other_certificates'),
                $request->input('video_introduce_url')
            );
        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return redirect(homeUrl());
    }
    #endregion
}