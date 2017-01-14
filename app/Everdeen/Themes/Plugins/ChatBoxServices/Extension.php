<?php
/**
 * Created by PhpStorm.
 * User: daiduong47
 * Date: 14/01/2017
 * Time: 22:07 PM
 */

namespace Katniss\Everdeen\Themes\Plugins\ChatBoxServices;

use Katniss\Everdeen\Themes\Extension as BaseExtension;

class Extension extends BaseExtension
{
    const NAME = 'chatbox_services';
    const DISPLAY_NAME = 'Chatbox Services';
    const DESCRIPTION = 'Set up Chatbox Services';

    public $cacheEnable;

    public function register()
    {
        enqueueThemeFooter($this->rawChatboxScript(), 'chatbox_services');
        /*if (!$this->cacheEnable) {
            if ($this->gaEnable) {
                enqueueThemeFooter($this->gaAsync ? $this->gaScriptAsync() : $this->gaScript(), 'ga_script');
            }
            if ($this->mixPanelEnable) {
                enqueueThemeFooter($this->mixPanelScript(), 'mix_panel_script');
            }
        } else {
            enqueueThemeFooter(Html5::js(AssetHelper::jsUrl($this::NAME)), $this::NAME);
            if ($this->gaEnable && $this->gaAsync) {
                enqueueThemeFooter($this->gaJsAsync(), 'ga_js_async');
            }
        }*/
    }

    public function rawChatboxScript()
    {
        return '<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src=\'https://embed.tawk.to/54f6736dbd5fa428704c651a/default\';
s1.charset=\'UTF-8\';
s1.setAttribute(\'crossorigin\',\'*\');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->';
    }
}
