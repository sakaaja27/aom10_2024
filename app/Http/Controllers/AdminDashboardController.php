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
        $ticketsdhdibayars = $transactionService->gettransaction()->where('status','paid')->count();
        $ticketdiambils = $transactionService->gettransaction()->where('status','paid')->where('presence',1)->orderBy('id_transaction','desc')->count();
        $penggunawebsites = $userservice->getuser();

        return view('pages.admin.dashboard',compact('ticketsdhdibayars','ticketdiambils','penggunawebsites'));
    }
}
