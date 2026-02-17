<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Τελευταία 100 logs
        $logs = Activity::latest()->take(100)->get();

        return view('activity-logs.index', compact('logs'));
    }
}