<?php


namespace App\Traits\Achievements;


use App\Concerns\Achievements;

trait CommentBased
{
    public function commentAchievements(bool $availed = true)
    {
        $achievements = collect(Achievements::COMMENT);

        if ($availed) {
            $achievements = $achievements->filter(function ($item) {
                return $item['count'] <= $this->comments->count();
            });
        } else {
            $achievements = $achievements->filter(function ($item) {
                return $item['count'] > $this->comments->count();
            });
        }

        return $achievements->pluck('title');
    }

    public function hasNewCommentAchievement(): bool
    {
        return collect(Achievements::COMMENT)->contains('count', $this->comments->count());
    }

    public function lastCommentAchievement()
    {
        if ($this->commentAchievements()->count()) {
            return $this->commentAchievements()->last();
        }
        return "";
    }
}
