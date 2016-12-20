<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-19
 * Time: 22:48
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Closure;
use Katniss\Everdeen\Http\Controllers\WebApiController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\ConversationRepository;
use Katniss\Everdeen\Themes\ConversationTheme;
use Katniss\Everdeen\Themes\Queue\JsQueue;
use Katniss\Everdeen\Themes\Theme;

class ConversationController extends WebApiController
{
    protected $conversationRepository;

    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            Theme::$overridden = new ConversationTheme();
            $app = app();
            $app['home_theme'] = $app->share(function () {
                return Theme::$overridden;
            });
            return $next($request);
        })->only('show');

        parent::__construct();

        $this->conversationRepository = new ConversationRepository();

        $this->middleware('device');
    }

    public function show(Request $request, $id)
    {
        if ($request->has('messages')) {
            return $this->messages($request, $id);
        }

        return $this->responseFail();
    }

    protected function messages(Request $request, $id)
    {
        $conversation = $this->conversationRepository->model($id);
        if ($conversation->isPublic) {
            $this->conversationRepository->updateCurrentDevice();
        }

        $users = $conversation->users;
        $devices = $conversation->devices;

        $jsQueue = new JsQueue();
        $jsQueue->add('abc', [
            'CONVERSATION_USERS' => json_encode($users->pluck('url_avatar_thumb', 'id')->all()),
            'CONVERSATION_DEVICES' => json_encode($devices->pluck('pivot.color', 'id')->all()),
        ], JsQueue::TYPE_VAR, ['CONVERSATION_USERS', 'CONVERSATION_DEVICES']);
        $jsQueue = $jsQueue->flush(false);

        return view('conversation', [
            'conversation' => $conversation,
            'conversation_users' => $users,
            'conversation_devices' => $devices,
            'js_queue' => $jsQueue,
        ]);
    }
}