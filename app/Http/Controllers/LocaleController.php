<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switchLocale($locale)
    {
        if (!in_array($locale, ['en', 'es'])) {
            abort(400);
        }

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
        
        session()->put('locale', $locale);

        return redirect()->back();
    }
}
