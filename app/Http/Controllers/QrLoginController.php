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
     * =========================
     * MONITOR – generate QR
     * =========================
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
     * =========================
     * HP – scan QR
     * =========================
     */
    public function scan($token)
    {
        $session = QrSession::where('token', $token)
            ->where('type', 'minigame')
            ->where('status', 'waiting')
            ->where('expired_at', '>', now())
            ->firstOrFail();

        return view('minigame.confirm', compact('token'));
    }

    /**
     * =========================
     * HP – CONNECT (PENTING)
     * =========================
     * ➜ status: logged_in
     */
    public function connect(Request $request, $token)
    {
        if (!Auth::check()) {
            abort(403, 'User belum login');
        }

        $session = QrSession::where('token', $token)
            ->where('type', 'minigame')
            ->where('status', 'waiting')
            ->where('expired_at', '>', now())
            ->firstOrFail();

        $session->update([
            'user_id' => Auth::id(),
            'status'  => 'logged_in'
        ]);

        // HP masuk halaman tunggu / info
        return view('minigame.confirm', [
            'token' => $token
        ]);
    }

    /**
     * =========================
     * MONITOR – polling status
     * =========================
     */
    public function status($token)
    {
        $session = QrSession::where('token', $token)->first();

        if (!$session) {
            return response()->json(['status' => 'expired']);
        }

        return response()->json([
            'status' => $session->status
        ]);
    }

    /**
     * =========================
     * MONITOR – mulai minigame
     * =========================
     * logged_in ➜ playing
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
     * =========================
     * FINISH GAME
     * =========================
     */
    public function finish(Request $request, $token)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0',
        ]);

        $session = QrSession::where('token', $token)
            ->where('type', 'minigame')
            ->where('status', 'playing')
            ->firstOrFail();

        Point::create([
            'user_id' => $session->user_id,
            'nilai'   => $request->nilai,
        ]);

        $session->update([
            'status' => 'finished'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mini game selesai'
        ]);
    }
}
