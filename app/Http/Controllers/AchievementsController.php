<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements'          => $user->unlockedAchievements(),
            'next_available_achievements'    => $user->nextAvailableAchievements(),
            'current_badge'                  => $user->currentBadge(),
            'next_badge'                     => $user->nextBadge(),
            'remaining_to_unlock_next_badge' => $user->remainingBadgeCount()
        ]);
    }
}
