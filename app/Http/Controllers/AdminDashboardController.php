<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionService $transactionService, UserService $userservice)
    {
        $ticketblmdikonfirms = $transactionService->gettransaction()->where('confirmation',0)->count();
        $ticketsdhdikonfirms = $transactionService->gettransaction()->where('confirmation',2)->count();
        $ticketsditolaks = $transactionService->gettransaction()->where('confirmation',1)->count();
        $ticketdiambils = $transactionService->gettransaction()->where('presence',1)->count();
        $penggunawebsites = $userservice->getuser()->where('role', '!=', 'ADMIN')->count();

        return view('pages.admin.dashboard',compact('ticketblmdikonfirms','ticketsdhdikonfirms','ticketsditolaks','ticketdiambils','penggunawebsites'));
    }
}
