<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->get();
        $upcomingEvents = Event::published()
            ->upcoming()
            ->where('visibility', 'public')
            ->orderBy('start_date')
            ->limit(6)
            ->get();

        return view('frontend.home', compact('sliders', 'upcomingEvents'));
    }

    public function about()
    {
        return view('frontend.about');
    }
}
