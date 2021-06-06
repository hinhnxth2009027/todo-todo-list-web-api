<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class JobStatus extends Enum
{
    const START = 1;
    const DONE = 0;
    const NOT_COMPLETED = -1;
}
