<?php


namespace App\Traits;


use App\Concerns\Badges;

trait HasBadge
{
    public function currentBadge(): string
    {
        return collect(Badges::ALL)->filter(function ($item) {
            return $item['count'] <= count($this->unlockedAchievements());
        })->last()['badge'];
    }

    public function nextBadge(): string
    {
        $badges = collect(Badges::ALL)->filter(function ($item) {
            return $item['count'] > count($this->unlockedAchievements());
        });

        if ($badges->first()) {
            return $badges->first()['badge'];
        }
        return "";
    }

    public function remainingBadgeCount(): int
    {
        return collect(Badges::ALL)->filter(function ($item) {
            return $item['count'] > count($this->unlockedAchievements());
        })->count();
    }

    public function hasNewBadge(): bool
    {
        return collect(Badges::ALL)->contains('count', count($this->unlockedAchievements()));
    }
}
