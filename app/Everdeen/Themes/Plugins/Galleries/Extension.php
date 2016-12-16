<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\Galleries;

use Katniss\Everdeen\Models\Media;
use Katniss\Everdeen\Repositories\MediaCategoryRepository;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Thunder\Shortcode\ShortcodeFacade;

class Extension extends BaseExtension
{
    const NAME = 'galleries';
    const DISPLAY_NAME = 'Galleries';
    const DESCRIPTION = 'Enable to embed galleries to layout';
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
        addFilter('post_content', new CallableObject(function ($content) {
            $facade = new ShortcodeFacade();
            $facade->addHandler('gallery', function (ShortcodeInterface $s) {
                static $galleryCount = 0;
                ++$galleryCount;
                $id = $s->getParameter('id');
                $photos = collect([]);
                if (!empty($id)) {
                    $mediaCategoryRepository = new MediaCategoryRepository();
                    $photos = $mediaCategoryRepository->model($id)->orderedMedia->where('type', Media::TYPE_PHOTO);
                }
                return view()->make(HomeThemeFacade::commonExtension($this::NAME, 'html'), [
                    'photos' => $photos,
                    'gallery_name' => 'post_content_gallery_' . $galleryCount,
                ])->render();
            });
            return $facade->process($content);
        }), 'ext:galleries');

        enqueueThemeHeader('<link rel="stylesheet" href="' . libraryAsset('fancybox/jquery.fancybox.css') . '">', 'galleries_widget_css');
        enqueueThemeFooter('<script src="' . libraryAsset('fancybox/jquery.fancybox.pack.js') . '"></script>', 'galleries_widget_js');
        enqueueThemeFooter('<script>
    $(function() {
        $(\'.embed-galleries .thumbnail a\').fancybox()
    });
</script>', 'embed_galleries');
    }
}