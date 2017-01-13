<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\SalaryJumpRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\NumberFormatHelper;

class SalaryReportController extends AdminController
{
    protected $salaryJumpRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'salary_report';
        $this->salaryJumpRepository = new SalaryJumpRepository();
    }

    public function index(Request $request)
    {
        $salaryJump = $this->salaryJumpRepository->getLast();

        $this->_title(trans('pages.admin_salary_report_title'));
        $this->_description(trans('pages.admin_salary_report_desc'));

        return $this->_index([
            'salary_jump' => $salaryJump,
            'salary_jump_value' => empty($salaryJump) ? '' : $salaryJump->formattedJumpCurrencyNoSign,
            'salary_jump_currency' => settings()->currency,
            'salary_jump_apply_from' => empty($salaryJump) ? '' : $salaryJump->formattedApplyFrom,
            'month_js_format' => DateTimeHelper::shortMonthPickerJsFormat(),
            'number_format_chars' => NumberFormatHelper::getInstance()->getChars(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jump' => ['required', 'regex:' . NumberFormatHelper::getInstance()->getRegEx(8, 2)],
            'apply_from' => 'required|date_format:' . DateTimeHelper::shortMonthFormat(),
            'currency' => 'required|in:' . implode(',', allCurrencyCodes()),
        ]);
        $errorRedirect = redirect(adminUrl('salary-report'))->withInput();
        if ($validator->fails()) {
            return $errorRedirect->withErrors($validator);
        }

        try {
            $date = DateTimeHelper::getInstance()
                ->fromFormat(DateTimeHelper::shortMonthFormat(), $request->input('apply_from'), true);
            $this->salaryJumpRepository->sync(
                NumberFormatHelper::getInstance()->fromFormat($request->input('jump')),
                $request->input('currency'),
                $date->format('Y'),
                $date->format('n')
            );
        } catch (KatnissException $ex) {
            return $errorRedirect->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('salary-report'));
    }
}
