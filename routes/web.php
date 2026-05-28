<?php

use App\Models\SitePage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $page = SitePage::where('slug', 'home')->where('is_active', true)->firstOrFail();
    return view('landing', compact('page'));
});
