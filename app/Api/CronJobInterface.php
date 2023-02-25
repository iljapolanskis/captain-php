<?php

namespace App\Api;

interface CronJobInterface
{
    /** @return void */
    public function run(): void;
}
