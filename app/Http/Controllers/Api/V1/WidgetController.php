<?php

namespace Katniss\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Katniss\Http\Controllers\ApiController;
use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Models\Themes\ThemeWidget;

class WidgetController extends ApiController
{
    public function updateOrder(Request $request)
    {
        if ($this->validate($request, [
            'placeholder' => 'required|in:' . implode(',', array_keys(HomeThemeFacade::placeholders())),
            'widget_ids' => 'required|array|exists:theme_widgets,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        $order = 0;
        foreach ($request->input('widget_ids') as $id) {
            ThemeWidget::where('id', $id)->update([
                'placeholder' => $request->input('placeholder'),
                'order' => ++$order,
            ]);
        }

        return $this->responseSuccess();
    }
}
