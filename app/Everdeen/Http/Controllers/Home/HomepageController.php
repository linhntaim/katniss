<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:09
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\StudyCourseRepository;
use Katniss\Everdeen\Repositories\StudyLevelRepository;
use Katniss\Everdeen\Repositories\StudyProblemRepository;

class HomepageController extends ViewController
{
    public function index()
    {
        $studyLevelRepository = new StudyLevelRepository();
        $studyProblemRepository = new StudyProblemRepository();
        $studyCourseRepository = new StudyCourseRepository();

        return $this->_any('home', [
            'study_levels' => $studyLevelRepository->getAll(),
            'study_problems' => $studyProblemRepository->getAll(),
            'study_courses' => $studyCourseRepository->getAll(),
        ]);
    }
}