<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Set the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\Response
     */
    public function setLanguage(Request $request, $locale)
    {
        // Check if the language is supported
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'id'; // Default to Indonesian
        }
        
        // Store the selected language in session
        session(['locale' => $locale]);
        
        // If you have a language cookie, you can set it here
        // return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}