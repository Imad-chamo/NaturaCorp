<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;

class JournalActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JournalActivite::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20);
        $users = User::select('id', 'name')->get();
        $actions = JournalActivite::select('action')->distinct()->pluck('action');

        return view('admin.logs.index', compact('logs', 'users', 'actions'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalActivite $journalActivite)
    {
        $journalActivite->delete();
        return redirect()->route('journal.index');
    }
}
