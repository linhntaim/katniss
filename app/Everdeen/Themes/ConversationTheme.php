<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-20
 * Time: 14:29
 */

namespace Katniss\Everdeen\Themes;


use Katniss\Everdeen\Themes\Queue\JsQueue;

class ConversationTheme extends Theme
{
    public function __construct()
    {
        parent::__construct(null);
    }

    protected function registerExtScripts($is_auth = false)
    {
        parent::registerExtScripts($is_auth);

        $this->extJsQueue->add('global_vars', [
            'KATNISS_USER_REQUIRED' => 'false',
        ], JsQueue::TYPE_VAR, ['KATNISS_USER_REQUIRED'], true);
        $this->extJsQueue->add('global-app-script', libraryAsset('katniss.home.js'));
    }
}