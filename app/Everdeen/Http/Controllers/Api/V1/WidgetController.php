<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;

class WidgetController extends ApiController
{
    protected $widgetRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->widgetRepository = new ThemeWidgetRepository();
    }

    public function updateOrder(Request $request)
    {
        if (!$this->validate($request, [
            'placeholder' => 'required|in:' . implode(',', array_keys(HomeThemeFacade::placeholders())),
            'widget_ids' => 'required|array|exists:theme_widgets,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->widgetRepository->updateSort($request->input('widget_ids'), $request->input('placeholder'));
        } catch (KatnissException $ex) {
            $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }
}
