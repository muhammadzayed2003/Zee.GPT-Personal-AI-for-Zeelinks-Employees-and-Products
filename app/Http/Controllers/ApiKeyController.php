<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = ApiKey::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('api-keys', compact('apiKeys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $plainKey = 'zee_' . Str::random(48);

        ApiKey::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'key_hash' => hash('sha256', $plainKey),
            'key_prefix' => substr($plainKey, 0, 12),
        ]);

        return redirect()
            ->route('api-keys')
            ->with('new_api_key', $plainKey);
    }

    public function destroy(ApiKey $apiKey)
    {
        abort_unless(
            $apiKey->user_id === auth()->id(),
            403
        );

        $apiKey->delete();

        return redirect()
            ->route('api-keys')
            ->with('success', 'API key revoked successfully.');
    }
}