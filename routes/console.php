<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('perpustakaan:detect-overdue')->dailyAt('00:05');
