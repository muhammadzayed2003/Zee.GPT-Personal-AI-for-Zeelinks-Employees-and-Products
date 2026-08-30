<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('permissions', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $user->update([
            'ai_workspace_access' => $request->boolean('ai_workspace_access'),
            'api_access' => $request->boolean('api_access'),
        ]);

        return back()->with('success', 'Permissions updated successfully.');
    }
}