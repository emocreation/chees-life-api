<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Facebook()
 * @method static static Instagram()
 * @method static static Youtube()
 */
final class SocialMedia extends Enum
{
    const Facebook = 0;
    const Instagram = 1;
    const Youtube = 2;
}
