<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class CollectionController extends Controller
{
    public function index(){
        $response = Http::withoutVerifying()
            ->timeout(15)
            ->get('https://api.tcgdex.net/v2/pt/sets');
    
        $collections = $response->successful() ? $response->json() : [];

        $collections = array_reverse($collections);

        return Inertia::render('Home/Index', [
            'collections' => $collections
        ]);
    }
}