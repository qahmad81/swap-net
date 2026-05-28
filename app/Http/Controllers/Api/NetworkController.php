<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\NetworkMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NetworkController extends Controller
{
    public function index(Request $request)
    {
        $networks = $request->user()->networkMembers()->with('network')->get()->pluck('network');
        return response()->json($networks);
    }

    public function show(Network $network)
    {
        return response()->json($network->load('owner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);

        $inviteCode = Str::random(8);
        $qrData = "https://swapnet.app/join/{$inviteCode}";

        $network = Network::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'description' => $request->description,
            'is_private' => $request->is_private ?? false,
            'owner_id' => $request->user()->id,
            'invite_code' => $inviteCode,
            'qr_data' => $qrData,
        ]);

        // Automatically join the creator as an admin
        NetworkMember::create([
            'network_id' => $network->id,
            'user_id' => $request->user()->id,
            'role' => 'admin',
        ]);

        return response()->json($network, 201);
    }

    public function join(Request $request, $inviteCode)
    {
        $network = Network::where('invite_code', $inviteCode)->firstOrFail();

        $member = NetworkMember::firstOrCreate([
            'network_id' => $network->id,
            'user_id' => $request->user()->id,
        ], [
            'role' => 'member'
        ]);

        return response()->json(['message' => 'Joined successfully', 'network' => $network]);
    }

    public function leave(Request $request, Network $network)
    {
        NetworkMember::where('network_id', $network->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Left network successfully']);
    }

    public function members(Network $network)
    {
        $members = $network->members()->with('user')->get();
        return response()->json($members);
    }
}
