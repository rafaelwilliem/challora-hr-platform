<?php

namespace App\Enums;

enum JobType: string
{
    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case INTERNSHIP = 'internship';
    case FREELANCE = 'freelance';
    case CONTRACT = 'contract';
}
