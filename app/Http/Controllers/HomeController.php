<?php

namespace App\Http\Controllers;


use App\Models\postingan;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $post = postingan::all();
        $ticket = Ticket::all();
        // dd($ticket);
        return view('index', compact('post', 'ticket'));
    }
    
    function sendMedpart()
    {
        return Redirect::away('https://wa.me/6281217126005');
    }
    function sendSponsorship()
    {
        return Redirect::away('https://wa.me/62881036457089');
    }
}
