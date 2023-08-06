<?php


namespace App\Enums;

enum OrderStatusEnum: string
{
    use \App\Traits\EnumHelper;

    case INCOMPLETE = 'incomplete';
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case RECEIVED = 'received';
    case RETURNED = 'returned';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    // case ON_HOLD = 'on_hold';
    // case COMPLETED = 'completed';
    // case PAID = 'paid';
    // case FAILED = 'failed';
    // case SUSPENDED = 'suspended';

    public static function getCssColor(string | self $value = null): string
    {
        return match ($value) {
            self::INCOMPLETE, self::PENDING, self::PROCESSING => 'info',
            self::SHIPPED, self::DELIVERED, self::RECEIVED => 'success',
            self::RETURNED, self::CANCELLED, self::REFUNDED => 'danger',
            default => 'secondary',
        };
    }
}
