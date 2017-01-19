<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-06
 * Time: 15:37
 */

namespace Katniss\Everdeen\Themes\Plugins\HomeClassRegister;

use Katniss\Everdeen\Repositories\StudyCourseRepository;
use Katniss\Everdeen\Repositories\StudyLevelRepository;
use Katniss\Everdeen\Repositories\StudyProblemRepository;
use Katniss\Everdeen\Themes\Widget as BaseWidget;

class Widget extends BaseWidget
{
    const NAME = 'home_class_register';
    const DISPLAY_NAME = 'Home Class Register';
    const EDITABLE = false;

    public function __init()
    {
        parent::__init();
    }

    public function viewHomeParams()
    {
        $studyLevelRepository = new StudyLevelRepository();
        $studyProblemRepository = new StudyProblemRepository();
        $studyCourseRepository = new StudyCourseRepository();

        return array_merge(parent::viewHomeParams(), [
            'study_levels' => $studyLevelRepository->getAll(),
            'study_problems' => $studyProblemRepository->getAll(),
            'study_courses' => $studyCourseRepository->getAll(),
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }
}