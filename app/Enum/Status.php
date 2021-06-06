<?php


namespace App\Enum;
use BenSampo\Enum\Enum;


class Status extends Enum
{
    const ACTIVE = 1;
    const NO_ACTIVE = 0;
    const DELETE = -1;
}
