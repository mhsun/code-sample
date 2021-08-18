<?php


namespace App\Concerns;


class Achievements
{
    /**
     * Achievements based on comments
     * @var array
     * */
    const COMMENT = [
        [
            'count' => 1,
            'title' => 'First Comment Written'
        ],
        [
            'count' => 3,
            'title' => '3 Comments Written'
        ],
        [
            'count' => 5,
            'title' => '5 Comments Written'
        ],
        [
            'count' => 10,
            'title' => '10 Comments Written'
        ],
        [
            'count' => 20,
            'title' => '20 Comments Written'
        ],
    ];

    /**
     * Achievements based on lessons
     * @var array
     * */
    const LESSON = [
        [
            'count' => 1,
            'title' => 'First Lesson Watched'
        ],
        [
            'count' => 5,
            'title' => '5 Lessons Watched'
        ],
        [
            'count' => 10,
            'title' => '10 Lessons Watched'
        ],
        [
            'count' => 25,
            'title' => '25 Five Lessons Watched'
        ],
        [
            'count' => 50,
            'title' => '50 Lessons Watched'
        ],
    ];
}
