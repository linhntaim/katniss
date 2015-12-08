<?php

namespace Katniss\Http\Controllers;

use Illuminate\Http\Request;
use Katniss\Http\Requests;
use Illuminate\Support\Facades\Validator;

class MultipleLocaleContentController extends ViewController
{
    public function validateMultipleLocaleData(Request $request, array $field_names, array $rules, &$data, &$successes, &$fails, &$old)
    {
        $data = [];
        $successes = [];
        $fails = [];
        $old = [];

        foreach ($field_names as $field_name) {
            $input = $request->input($field_name);
            if (empty($input)) {
                $input = $request->file($field_name);
            }
            if (empty($input)) continue;
            foreach ($input as $locale => $value) {
                if (!isset($data[$locale])) {
                    $data[$locale] = [];
                }
                $data[$locale][$field_name] = $value;
                $old[$field_name . '_' . $locale] = $value;
            }
        }

        if (empty($rules)) {
            $successes[] = array_keys($data);
            return;
        }
        foreach ($data as $locale => $inputs) {
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                $fails[] = $validator;
            } else {
                $successes[] = $locale;
            }
        }
    }
}
