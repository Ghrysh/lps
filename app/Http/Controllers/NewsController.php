<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;

class NewsController extends Controller
{
    public function slider()
    {
        $sliders = \App\Models\Slider::orderBy('order', 'asc')->get();

        return view('new.slider', compact('sliders'));
    }
}
