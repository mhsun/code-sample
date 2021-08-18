<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserAchievementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function default_values_will_be_shown_if_user_has_no_achievements()
    {
        $this->get("/users/{$this->user->id}/achievements")
            ->assertOk()
            ->assertJson([
                'unlocked_achievements'          => [],
                'next_available_achievements'    => [
                    "First Comment Written",
                    "3 Comments Written",
                    "5 Comments Written",
                    "10 Comments Written",
                    "20 Comments Written",
                    "First Lesson Watched",
                    "5 Lessons Watched",
                    "10 Lessons Watched",
                    "25 Five Lessons Watched",
                    "50 Lessons Watched"
                ],
                'current_badge'                  => "Beginner",
                'next_badge'                     => "Intermediate",
                'remaining_to_unlock_next_badge' => 3
            ]);
    }

    /** @test */
    public function users_achievement_will_be_shown_if_there_any()
    {
        Comment::factory()->count(5)->create([
            'user_id' => $this->user->id
        ]);

        $lessons = Lesson::factory()->count(20)->create();

        $lessons->take(10)->each(function ($lesson) {
            DB::table('lesson_user')->insert([
                'user_id'   => $this->user->id,
                'lesson_id' => $lesson->id,
                'watched'   => true
            ]);
        });

        $this->get("/users/{$this->user->id}/achievements")
            ->assertOk()
            ->assertJson([
                'unlocked_achievements'          => $this->user->unlockedAchievements(),
                'next_available_achievements'    => $this->user->nextAvailableAchievements(),
                'current_badge'                  => $this->user->currentBadge(),
                'next_badge'                     => $this->user->nextBadge(),
                'remaining_to_unlock_next_badge' => $this->user->remainingBadgeCount()
            ]);

        $this->assertCount(6, $this->user->unlockedAchievements());
        $this->assertCount(4, $this->user->nextAvailableAchievements());
        $this->assertEquals("Intermediate", $this->user->currentBadge());
        $this->assertEquals("Advanced", $this->user->nextBadge());
        $this->assertEquals(2, $this->user->remainingBadgeCount());
    }
}
