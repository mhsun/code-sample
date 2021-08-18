<?php


namespace App\Traits\Achievements;


use App\Concerns\Achievements;

trait LessonBased
{
    public function lessonAchievements(bool $availed = true)
    {
        $achievements = collect(Achievements::LESSON);

        if ($availed) {
            $achievements = $achievements->filter(function ($item) {
                return $item['count'] <= $this->watched->count();
            });
        } else {
            $achievements = $achievements->filter(function ($item) {
                return $item['count'] > $this->watched->count();
            });
        }

        return $achievements->pluck('title');
    }

    public function hasNewLessonAchievement(): bool
    {
        return collect(Achievements::LESSON)->contains('count', $this->watched->count());
    }

    public function lastLessonAchievement()
    {
        if ($this->lessonAchievements()->count()) {
            return $this->lessonAchievements()->last();
        }
        return "";
    }
}
