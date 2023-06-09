<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Female()
 * @method static static Male()
 */
final class Gender extends Enum
{
    const Female = 'F';
    const Male = 'M';
}
