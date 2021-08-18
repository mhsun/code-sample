<?php

namespace Tests\Feature;


use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Listeners\HandleLessonAchievement;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonBasedAchievementUnlockedTest extends TestCase
{
    use RefreshDatabase;

    protected $lessons;
    protected $user;
    protected $listener;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->lessons = Lesson::factory()->count(50)->create();

        Event::fake();

        Event::assertNotDispatched(AchievementUnlocked::class);
        Event::assertNotDispatched(LessonWatched::class);

        $this->listener = new HandleLessonAchievement();
    }

    /** @test */
    public function lesson_watched_event_has_listeners_to_listen()
    {
        Event::fake();

        $this->assertTrue(Event::hasListeners(LessonWatched::class));
    }

    /** @test */
    public function achievement_will_be_unlocked_on_first_video_watched()
    {
        $this->lessons->take(1)->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->listener->handle(new LessonWatched($this->lessons->first(), $this->user));

        $this->assertCount(1, $this->user->watched);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(1, $this->user->unlockedAchievements());
        $this->assertCount(9, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function achievement_will_be_unlocked_on_fifth_video_watch()
    {
        $this->lessons->take(5)->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->listener->handle(new LessonWatched($this->lessons->last(), $this->user));

        $this->assertCount(5, $this->user->watched);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(2, $this->user->unlockedAchievements());
        $this->assertCount(8, $this->user->nextAvailableAchievements());
    }


    /** @test */
    public function achievement_will_be_unlocked_on_tenth_video_watched()
    {
        $this->lessons->take(10)->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->listener->handle(new LessonWatched($this->lessons->last(), $this->user));

        $this->assertCount(10, $this->user->watched);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(3, $this->user->unlockedAchievements());
        $this->assertCount(7, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function achievement_will_be_unlocked_on_twenty_fifth_video_watch()
    {
        $this->lessons->take(25)->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->listener->handle(new LessonWatched($this->lessons->last(), $this->user));

        $this->assertCount(25, $this->user->watched);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(4, $this->user->unlockedAchievements());
        $this->assertCount(6, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function achievement_will_be_unlocked_on_fifty_video_watched()
    {
        $this->lessons->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->listener->handle(new LessonWatched($this->lessons->last(), $this->user));

        $this->assertCount(50, $this->user->watched);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(5, $this->user->unlockedAchievements());
        $this->assertCount(5, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function last_lesson_based_achievement_will_remain_unchanged_after_fifty_video_watched()
    {
        $this->lessons = Lesson::factory()->count(51)->create();

        $this->lessons->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->listener->handle(new LessonWatched($this->lessons->last(), $this->user));

        $this->assertCount(51, $this->user->watched);

        Event::assertNotDispatched(AchievementUnlocked::class);

        $this->assertCount(5, $this->user->unlockedAchievements());
        $this->assertCount(5, $this->user->nextAvailableAchievements());
    }
}
