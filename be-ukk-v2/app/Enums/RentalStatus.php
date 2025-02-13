<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RentalStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'P';
    case Active = 'D';
    case Completed = 'S';
    case Cancelled = 'B';
    case Rejected = 'T';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Active => 'Sedang Disewa',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
            self::Rejected => 'Ditolak',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Active => 'primary',
            self::Completed => 'success',
            self::Cancelled, self::Rejected => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-m-clock',
            self::Active => 'heroicon-m-shopping-bag',
            self::Completed => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
            self::Rejected => 'heroicon-m-x-mark',
        };
    }
}