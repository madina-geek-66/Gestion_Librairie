<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $livres = Livre::orderBy('created_at', 'desc')->limit(4)->get();
        return view('home', ['livres' => $livres]);
    }
}
