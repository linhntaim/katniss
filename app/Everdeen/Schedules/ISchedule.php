<?php

namespace Katniss\Everdeen\Schedules;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

interface ISchedule
{
	public function run(ConsoleKernel $kernel = null);
}