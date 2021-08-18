<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function currentBadge_method_returns_beginner_if_no_achievement_found()
    {
        $user = User::factory()->create();

        $this->assertEquals("Beginner", $user->currentBadge());
    }

    /** @test */
    public function currentBadge_method_returns_right_badge_name()
    {
        $user = User::factory()->create();

        $this->assertEquals("Beginner", $user->currentBadge());

        Comment::factory()->count(4)->create([
            "user_id" => $user->id
        ]);

        $this->assertEquals("Beginner", $user->currentBadge());
    }

    /** @test */
    public function nextBadge_method_returns_right_badge_name()
    {
        $user = User::factory()->create();

        $this->assertEquals("Beginner", $user->currentBadge());

        $this->assertEquals("Intermediate", $user->nextBadge());
    }
}
