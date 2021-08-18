<?php


namespace App\Concerns;


class Badges
{
    /**
     * Badges based on achievements
     * @var array
     * */
    public const ALL = [
        [
            'count' => 0,
            'badge' => 'Beginner'
        ],
        [
            'count' => 4,
            'badge' => 'Intermediate'
        ],
        [
            'count' => 8,
            'badge' => 'Advanced'
        ],
        [
            'count' => 10,
            'badge' => 'Master'
        ]
    ];
}
