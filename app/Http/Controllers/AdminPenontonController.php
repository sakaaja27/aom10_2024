<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\transactionService;
use App\Services\UserService;
use App\Models\transaction;
use Auth;
use Midtrans\Snap;
use Midtrans\Config;

class AdminPenontonController extends Controller
{
    private $transactionservice;
    private $userservice;
    public function __construct(transactionService $transactionservice, UserService $userservice)
    {
        $this->transactionservice = $transactionservice;
        $this->userservice = $userservice;
    }
    public function index(transactionService $transactionService,UserService $userservice){
        $ticketblmdikonfirms = $transactionService->gettransaction()->where('confirmation',0)->orderBy('created_at','asc')->get();
        $ticketsdhdikonfirms = $transactionService->gettransaction()->where('confirmation',2)->where('presence',0)->orderBy('id_transaction','desc')->get();
        $ticketsditolaks = $transactionService->gettransaction()->where('confirmation',1)->orderBy('id_transaction','asc')->get();
        $ticketdiambils = $transactionService->gettransaction()->where('presence',1)->orderBy('id_transaction','desc')->get();
        $penggunawebsites = $userservice->getuser();
        return view('pages.admin.penonton',compact('ticketblmdikonfirms','ticketsdhdikonfirms','ticketsditolaks','ticketdiambils','penggunawebsites'));
    }
    public function getticketpenonton($idtransaction){
        $data1 = $this->transactionservice->gettransactionwithmidtrans($idtransaction);
        $data2 = $this->transactionservice->gettransaction()->where('id_transaction',$idtransaction)->first();
        $data = array_merge($data1,$data2->toArray());
        return response()->json($data);
    }
    public function confirm(transactionService $transactionService,Request $request,$id)
    {
        // get request
        $confirmCode = $request->status_konfirmasi;
        $status = $request->status;


        // proccess confirm user
        $data = $transactionService->confirmtransaction($id, $confirmCode,$status)->first();
        // data email
        $dataEmail = [
            'name' =>   $data->user->name,
            'email' => $data->user->email,
            'url' => route('home')
        ];
        // check confirm code
        switch ($confirmCode):
            case 1:
                toast('User Berhasil Ditolak!', 'success');
                break;

            default:
                toast('User Berhasil Dikonfirmasi!', 'success');
                break;
        endswitch;

        // send email
        $transactionService->sendUserConfirmMail($confirmCode, $dataEmail);

        return redirect()->back();
    }
}
