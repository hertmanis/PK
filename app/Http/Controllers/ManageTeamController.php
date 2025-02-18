<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ManageTeamController extends Controller
{
    public function index()
    {
        $coach = Auth::user();

        if ($coach->team) {
            // Fetch all users (both players and coach) in the same team
            $teamMembers = User::where('team_id', $coach->team->id)->get();
        } else {
            $teamMembers = collect(); // Empty collection
        }

        return view('dashboard.coach.manage-team', compact('teamMembers'));
    }
}
