<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\transactionService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use GuzzleHttp\Client;
use Midtrans\Config;
use Midtrans\Transaction;

class AdminPresenceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(UserService $userService)
    {
        return view('pages.admin.presence');
    }

    public function getuserdata(transactionService $transactionservice, Request $request)
    {
        $datauser = $transactionservice->gettransactionbyidandemail($request->code, $request->email)->get();
        if (!empty($datauser->first())) {
            return response()->json($datauser);
        } else {
            return response()->json(null);
        }
    }

    // process precensing / use tikcet
    public function presence(transactionService $transactionService, Request $request)
    {
        $data = $transactionService->gettransactionbyidandemail($request->code, null);
        $data->update([
            'presence' => 1,
        ]);
        toast('Konfirmasi Tiket Pengguna Berhasil!', 'success');
        return redirect()->route('admin.presence');
    }

}
