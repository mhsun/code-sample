<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleCommentAchievement
{
    public function __construct()
    {

    }
    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle($event)
    {
        if ($event->comment->user->hasNewCommentAchievement()) {
            event(new AchievementUnlocked(
                $event->comment->user->lastCommentAchievement(),
                $event->comment->user
            ));
        }

        if ($event->comment->user->hasNewBadge()) {
            event(new BadgeUnlocked(
                $event->comment->user->currentBadge(),
                $event->comment->user
            ));
        }
    }
}
