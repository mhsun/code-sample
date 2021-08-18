<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\HandleCommentAchievement;
use App\Listeners\HandleLessonAchievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BadgeUnblockTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Event::fake();

        Event::assertNotDispatched(BadgeUnlocked::class);
        Event::assertNotDispatched(CommentWritten::class);
        Event::assertNotDispatched(LessonWatched::class);
    }

    /** @test */
    public function badge_unblocked_event_will_be_fired_on_milestone_complete()
    {
        $comments = Comment::factory()->count(10)->create([
            'user_id' => $this->user->id
        ]);

        $listener = new HandleCommentAchievement();
        $listener->handle(new CommentWritten($comments->last()));

        $this->assertCount(10, $this->user->comments);

        $this->assertCount(4, $this->user->unlockedAchievements());

        Event::assertDispatched(BadgeUnlocked::class);

        $lessons = Lesson::factory()->count(25)->create();

        $lessons->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $listener = new HandleLessonAchievement();
        $listener->handle(new LessonWatched($lessons->last(), $this->user));

        $this->user->refresh();

        $this->assertCount(25, $this->user->watched);

        $this->assertCount(8, $this->user->unlockedAchievements());

        Event::assertDispatched(BadgeUnlocked::class);
    }
}
