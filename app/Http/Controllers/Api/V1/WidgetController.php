<?php

namespace Katniss\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Katniss\Http\Requests;
use Katniss\Http\Controllers\Controller;
use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Models\Themes\ThemeWidget;

class WidgetController extends Controller
{
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placeholder' => 'required|in:' . implode(',', array_keys(HomeThemeFacade::placeholders())),
            'widget_ids' => 'required|array|exists:theme_widgets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => $validator->errors()->all()
            ]);
        }

        $order = 0;
        foreach ($request->input('widget_ids') as $id) {
            ThemeWidget::where('id', $id)->update([
                'placeholder' => $request->input('placeholder'),
                'order' => ++$order,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
