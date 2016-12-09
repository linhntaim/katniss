<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\ContactForm;

use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Controllers\ContactFormAdminController;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Controllers\ContactFormHomeController;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Controllers\ContactFormWebApiController;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Extension extends BaseExtension
{
    const NAME = 'contact_form';
    const DISPLAY_NAME = 'Contact Form';
    const DESCRIPTION = 'Enable to embed contact form to layout and manage submitted data from contact form';
    const EDITABLE = false;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        _kWidgets([Widget::NAME => Widget::class]);
    }

    public function register()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add( // add a menu item
                    addExtraUrl('admin/contact-forms', adminUrl('extra')),
                    trans('contact_form.page_contact_forms_title'),
                    '<i class="fa fa-table"></i> <span>', '</span>'
                );
            }
            return $menu;
        }), 'ext:contact_form:menu');

        addExtraRouteResourceTriggers('admin/contact-forms', ContactFormAdminController::class);
        addExtraRouteResourceTriggers('contact-forms', ContactFormHomeController::class);
        addExtraRouteResourceTriggers('web-api/contact-forms', ContactFormWebApiController::class);
    }
}