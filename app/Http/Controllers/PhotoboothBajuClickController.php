<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoboothBajuClick;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PhotoboothBajuClickController extends Controller
{
    /**
     * Simpan event klik baju photobooth
     */
    public function store(Request $request)
    {
        // 🔹 Log request masuk (debug ringan)
        Log::info('[Photobooth] Click request received', [
            'payload' => $request->all(),
            'ip' => $request->ip(),
        ]);

        $validated = $request->validate([
            'photobooth_dataset_id' => [
                'required',
                'uuid',
                Rule::exists('photobooth_datasets', 'id')
            ],
            'gender_mode' => [
                'required',
                Rule::in([
                    'pria',
                    'wanita',
                    'pria_wanita',
                    'pria_pria',
                    'wanita_wanita'
                ])
            ],
            'people_count' => 'required|integer|min:1|max:2',
        ]);

        $click = PhotoboothBajuClick::create([
            'photobooth_dataset_id' => $validated['photobooth_dataset_id'],
            'gender_mode' => $validated['gender_mode'],
            'people_count' => $validated['people_count'],
            'clicked_at' => now(),
        ]);

        // Log sukses simpan
        Log::info('[Photobooth] Baju clicked logged', [
            'click_id' => $click->id,
            'dataset_id' => $click->photobooth_dataset_id,
            'gender_mode' => $click->gender_mode,
            'people_count' => $click->people_count,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Click logged'
        ]);
    }
}
