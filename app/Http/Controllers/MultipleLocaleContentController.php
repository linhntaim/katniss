<?php

namespace Katniss\Http\Controllers;

use Illuminate\Http\Request;
use Katniss\Http\Requests;
use Illuminate\Support\Facades\Validator;

class MultipleLocaleContentController extends ViewController
{
    public function validateMultipleLocaleData(Request $request, array $fieldNames, array $rules, &$data, &$successes, &$fails, &$old, array $htmlInputs = [])
    {
        $emptyHtmlInputs = empty($htmlInputs);
        $allSupportedLocaleCodes = allSupportedLocaleCodes();
        $data = [];
        $successes = [];
        $fails = [];
        $old = [];

        foreach ($fieldNames as $fieldName) {
            $input = $request->input($fieldName);
            if (empty($input)) {
                $input = $request->file($fieldName);
            }
            if (empty($input)) continue;
            foreach ($input as $locale => $value) {
                if (!in_array($locale, $allSupportedLocaleCodes)) {
                    continue;
                }
                if (!isset($data[$locale])) {
                    $data[$locale] = [];
                }
                if (!$emptyHtmlInputs && array_key_exists($fieldName, $htmlInputs)) {
                    $value = clean($value, $htmlInputs[$fieldName]);
                }
                $data[$locale][$fieldName] = $value;
                $old[$fieldName . '_' . $locale] = $value;
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
