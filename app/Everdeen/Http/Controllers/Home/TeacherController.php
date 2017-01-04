<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:05
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\UserRepository;

class TeacherController extends ViewController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'teacher';
        $this->userRepository = new UserRepository();
    }

    public function signUp()
    {
        return $this->_any('sign_up', [
            'skype_id' => 'skype_id',
            'skype_name' => 'Skype',
            'hot_line' => '1900 1000',
            'email' => 'example@example.com',
        ]);
    }
}