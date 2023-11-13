<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Amount()
 * @method static static Percentage()
 */
final class CouponType extends Enum
{
    const Amount = 'amount';
    const Percentage = 'percentage';
}
