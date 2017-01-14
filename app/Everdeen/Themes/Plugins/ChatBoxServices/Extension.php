<?php
/**
 * Created by PhpStorm.
 * User: daiduong47
 * Date: 14/01/2017
 * Time: 22:07 PM
 */

namespace Katniss\Everdeen\Themes\Plugins\ChatBoxServices;

use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Utils\AssetHelper;
use Katniss\Everdeen\Utils\HtmlTag\Html5;

class Extension extends BaseExtension
{
    const NAME              = 'chatbox_services';
    const DISPLAY_NAME      = 'Chatbox Services';
    const DESCRIPTION       = 'Set up Chatbox Services';
    const EDITABLE          = true;
    const DEFAULT_TAWKTO_ID = '54f6736dbd5fa428704c651a';

    public $cacheEnable;
    public $chatboxEnable;
    public $chatboxId;
    public $customChatbox;

    public function register ()
    {
        if (!$this->cacheEnable && $this->chatboxEnable) {
            enqueueThemeFooter($this->rawChatboxScript(), 'chatbox_services');
        } else {
            enqueueThemeFooter(Html5::js(AssetHelper::jsUrl($this::NAME)), $this::NAME);
        }
    }

    public function chatboxScript ()
    {
        return 'var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src=\'https://embed.tawk.to/' . $this->chatboxId . '/default\';
                s1.charset=\'UTF-8\';
                s1.setAttribute(\'crossorigin\',\'*\');
                s0.parentNode.insertBefore(s1,s0);
                })();';
    }

    public function rawChatboxScript ()
    {
        return '<!--Start of Tawk.to Script--> <script type="text/javascript">' . $this->chatboxScript() . '</script> <!--End of Tawk.to Script-->';
    }

    public function cacheScripts ()
    {
        if ($this->cacheEnable) {
            $cache = '';
            if ($this->chatboxEnable) {
                $cache .= $this->chatboxScript() . PHP_EOL;
            }
            if (!empty($this->customChatbox)) {
                $cache = $this->customChatbox . PHP_EOL;
            }
            if (!empty($cache)) {
                AssetHelper::cacheJs($this::NAME, $cache);
            }
        }
    }

    protected function __init ()
    {
        parent::__init();

        $this->cacheEnable   = !empty($this->data['cache_enable']) && $this->data['cache_enable'] == 1;

        $this->chatboxEnable = !empty($this->data['chatbox_enable']) && $this->data['chatbox_enable'] == 1;
        $this->chatboxId     = empty($this->data['chatbox_id']) ? $this::DEFAULT_TAWKTO_ID : $this->data['chatbox_id'];

        $this->customChatbox = empty($this->data['custom_chatbox']) ? '' : $this->data['custom_chatbox'];

        if (!$this->chatboxEnable) {
            $this->cacheEnable = false;
        }
    }

    public function viewAdminParams ()
    {
        return array_merge(parent::viewAdminParams(), [
            'cache_enable'   => $this->cacheEnable,
            'chatbox_enable' => $this->chatboxEnable,
            'chatbox_id'     => $this->chatboxId,
            'custom_chatbox' => $this->customChatbox,
        ]);
    }

    public function fields ()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            'cache_enable',
            'chatbox_enable',
            'chatbox_id',
            'custom_chatbox',
        ]);
    }

    public function validationRules ()
    {
        $validationRules = parent::validationRules();
        return array_merge($validationRules, [
            'cache_enable'   => 'sometimes|in:1',
            'chatbox_enable' => 'sometimes|in:1',
            'chatbox_id'     => 'required_without:custom_chatbox',
            'custom_chatbox' => 'required_without:chatbox_id',
        ]);
    }

    public function save (array $data = [], array $localizedData = [])
    {
        $result = parent::save($data, $localizedData);

        if ($result === true) {
            $extension = new Extension();
            $extension->cacheScripts();
        }

        return $result;
    }
}
