<?php


namespace App\Traits;


use App\Traits\Achievements\CommentBased;
use App\Traits\Achievements\LessonBased;

trait HasAchievement
{
    use CommentBased, LessonBased;

    public function unlockedAchievements(): array
    {
        return $this->commentAchievements()
            ->merge($this->lessonAchievements())
            ->toArray();
    }

    public function nextAvailableAchievements()
    {
        return $this->commentAchievements(false)
            ->merge($this->lessonAchievements(false))
            ->toArray();
    }
}
