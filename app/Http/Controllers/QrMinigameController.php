<?php

namespace App\Http\Controllers;

use App\Models\QrSession;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrMinigameController extends Controller
{
    /**
     * MONITOR – generate QR
     */
    public function index()
    {
        QrSession::where('expired_at', '<', now())->delete();

        $session = QrSession::create([
            'token'      => Str::uuid(),
            'type'       => 'minigame',
            'status'     => 'waiting',
            'expired_at' => now()->addMinutes(5),
        ]);

        return view('monitor.minigame', [
            'token' => $session->token
        ]);
    }

    /**
     * HP – scan QR (AUTO CONNECT)
     */
    public function scan($token)
    {
        if (!Auth::check()) {
            abort(403, 'User belum login');
        }

        $session = QrSession::where('token', $token)
            ->where('type', 'minigame')
            ->where('expired_at', '>', now())
            ->firstOrFail();

        // ⬅️ AUTO CONNECT DI SINI
        if ($session->status === 'waiting') {
            $session->update([
                'user_id' => Auth::id(),
                'status'  => 'logged_in'
            ]);
        }

        // HP cuma lihat halaman tunggu
        return view('minigame.waiting', compact('token'));
    }

    /**
     * MONITOR – polling status
     */
    public function status($token)
    {
        $session = QrSession::where('token', $token)->first();

        if (! $session) {
            return response()->json(['status' => 'expired']);
        }

        return response()->json([
            'status' => $session->status
        ]);
    }

    /**
     * MONITOR – mulai game
     */
    public function play($token)
    {
        $session = QrSession::where('token', $token)
            ->where('type', 'minigame')
            ->whereIn('status', ['logged_in', 'playing'])
            ->firstOrFail();

        if ($session->status === 'logged_in') {
            $session->update(['status' => 'playing']);
        }

        return view('minigame.minigame', compact('token'));
    }

    /**
     * FINISH
     */
    public function finish(Request $request, $token)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0',
        ]);

        $session = QrSession::where('token', $token)
            ->where('status', 'playing')
            ->firstOrFail();

        Point::create([
            'user_id' => $session->user_id,
            'nilai'   => $request->nilai,
        ]);

        $session->update(['status' => 'finished']);

        return response()->json(['success' => true]);
    }
}
