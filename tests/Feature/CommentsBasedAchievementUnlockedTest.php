<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\HandleCommentAchievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentsBasedAchievementUnlockedTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $listener;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Event::fake();

        Event::assertNotDispatched(AchievementUnlocked::class);
        Event::assertNotDispatched(CommentWritten::class);

        $this->listener = new HandleCommentAchievement();
    }

    /** @test */
    public function comment_written_event_has_listeners_to_listen()
    {
        Event::fake();

        $this->assertTrue(Event::hasListeners(CommentWritten::class));
    }

    /** @test */
    public function achievement_will_be_unlocked_on_first_comment()
    {
        $comments = Comment::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->listener->handle(new CommentWritten($comments->first()));

        $this->assertCount(1, $this->user->comments);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(1, $this->user->unlockedAchievements());
        $this->assertCount(9, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function achievement_will_be_unlocked_on_third_comment()
    {
        $comments = Comment::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $this->listener->handle(new CommentWritten($comments->last()));

        $this->assertCount(3, $this->user->comments);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(2, $this->user->unlockedAchievements());
        $this->assertCount(8, $this->user->nextAvailableAchievements());
    }


    /** @test */
    public function achievement_will_be_unlocked_on_fifth_comment()
    {
        $comments = Comment::factory()->count(5)->create([
            'user_id' => $this->user->id
        ]);

        $this->listener->handle(new CommentWritten($comments->last()));

        $this->assertCount(5, $this->user->comments);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(3, $this->user->unlockedAchievements());
        $this->assertCount(7, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function achievement_will_be_unlocked_on_tenth_comment()
    {
        $comments = Comment::factory()->count(10)->create([
            'user_id' => $this->user->id
        ]);

        $this->listener->handle(new CommentWritten($comments->last()));

        $this->assertCount(10, $this->user->comments);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(4, $this->user->unlockedAchievements());
        $this->assertCount(6, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function achievement_will_be_unlocked_on_twenty_comment()
    {
        $comments = Comment::factory()->count(20)->create([
            'user_id' => $this->user->id
        ]);

        $this->listener->handle(new CommentWritten($comments->last()));

        $this->assertCount(20, $this->user->comments);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertCount(5, $this->user->unlockedAchievements());
        $this->assertCount(5, $this->user->nextAvailableAchievements());
    }

    /** @test */
    public function last_comment_based_achievement_will_remain_unchanged_after_twenty_comments()
    {
        $comments = Comment::factory()->count(25)->create([
            'user_id' => $this->user->id
        ]);

        $this->listener->handle(new CommentWritten($comments->last()));

        $this->assertCount(25, $this->user->comments);

        Event::assertNotDispatched(AchievementUnlocked::class);

        $this->assertCount(5, $this->user->unlockedAchievements());
        $this->assertCount(5, $this->user->nextAvailableAchievements());
    }
}
