<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Practice;

class PracticeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        if ($user->team) {
            $practices = Practice::where('team_id', $user->team->id)->get();
        } else {
            $practices = collect(); // Empty collection if no team
        }
    
        // Redirect based on user role
        if ($user->role == 0) { // Assuming role 0 is for coaches
            return view('dashboard.coach.coach-practice', compact('practices', 'user'));
        } else {
            return view('dashboard.player.player-practice', compact('practices', 'user'));
        }
    }
    


    public function create()
    {
        return view('dashboard.coach.create-practice');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:today',
            'type' => 'required|in:trenins,spele',
        ]);
    
        Practice::create([
            'team_id' => Auth::user()->team->id,
            'coach_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'type' => $request->type,
        ]);
    
        return redirect()->route('practices.index')->with('success', 'Aktivitāte veiksmīgi ieplānota.');
    }
    

    public function destroy($id)
    {
        $practice = Practice::findOrFail($id);

        if ($practice->coach_id !== Auth::id()) {
            return redirect()->route('practices.index')->with('error', 'You can only delete your own practices.');
        }

        $practice->delete();

        return redirect()->route('practices.index')->with('success', 'Practice deleted successfully.');
    }

    public function getParticipationStatus($practiceId, $userId)
    {
        $participation = Participation::where('practice_id', $practiceId)
                                       ->where('user_id', $userId)
                                       ->first();

        $status = $participation ? $participation->status : 'nebus';

        return response()->json(['status' => $status]);
    }

    // Update participation status
    public function updateParticipationStatus($practiceId, $userId, Request $request)
    {
        $status = $request->input('status');

        // Check if participation record exists
        $participation = Participation::updateOrCreate(
            ['practice_id' => $practiceId, 'user_id' => $userId],
            ['status' => $status]
        );

        // Return success message
        return response()->json(['message' => 'Jūsu piedalīšanās statuss ir atjaunināts!']);
    }

}
