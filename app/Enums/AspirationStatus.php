<?php

namespace App\Enums;

enum AspirationStatus: string
{
    case Pending = 'pending';
    case Reviewed = 'reviewed';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Rejected = 'rejected';

    /**
     * Get a human-readable label for this status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Reviewed => 'Reviewed',
            self::InProgress => 'In Progress',
            self::Resolved => 'Resolved',
            self::Rejected => 'Rejected',
        };
    }
}
