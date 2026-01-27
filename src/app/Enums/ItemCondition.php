<?php

namespace App\Enums;

enum ItemCondition: int
{
    case LikeNew = 1;
    case VeryGood = 2;
    case Good = 3;
    case Acceptable = 4;

    public function label(): string
    {
        return match ($this) {
            self::LikeNew => '良好',
            self::VeryGood => '目立った傷や汚れなし',
            self::Good => 'やや傷や汚れあり',
            self::Poor => '状態が悪い'
        };
    }
}