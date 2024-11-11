<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 0;
    case COMPLETE = 1;
    case CANCEL = 2;
    case DELIVERED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::DELIVERED => __('Delivered'),
            self::COMPLETE => __('Complete'),
            self::CANCEL => __('Cancel'),
        };
    }
}
