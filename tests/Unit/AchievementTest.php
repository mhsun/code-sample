<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unlockedAchievements_method_returns_empty_array_on_no_achievement()
    {
        $user = User::factory()->create();

        $this->assertCount(0, $user->unlockedAchievements());
        $this->assertEmpty($user->unlockedAchievements());
    }

    /** @test */
    public function unlockedAchievements_method_returns_array_containing_achievements()
    {
        $user = User::factory()->create();
        Comment::factory()->count(5)->create([
            "user_id" => $user->id
        ]);

        $this->assertCount(3, $user->unlockedAchievements());
        $this->assertIsArray($user->unlockedAchievements());
    }

    /** @test */
    public function nextAvailableAchievements_method_returns_all_achievements_on_no_achievement_availed()
    {
        $user = User::factory()->create();

        $this->assertCount(10, $user->nextAvailableAchievements());
        $this->assertIsArray($user->nextAvailableAchievements());
    }

    /** @test */
    public function nextAvailableAchievements_returns_un_availed_list_of_achievements()
    {
        $user = User::factory()->create();
        Comment::factory()->count(5)->create([
            "user_id" => $user->id
        ]);

        $this->assertCount(7, $user->nextAvailableAchievements());
        $this->assertIsArray($user->nextAvailableAchievements());
    }
}
