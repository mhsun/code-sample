<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleLessonAchievement
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->user->hasNewLessonAchievement()) {
            event(new AchievementUnlocked(
                $event->user->lastLessonAchievement(),
                $event->user
            ));
        }

        if ($event->user->hasNewBadge()) {
            event(new BadgeUnlocked(
                $event->user->currentBadge(),
                $event->user
            ));
        }
    }
}
