<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return view('pages.admin.ticket.tickets', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ]);
        $create = Ticket::create($request->all());
        if ($create) {
            toast('Data berhasil ditambahkan!', 'success');
            return redirect()->route('Ticket.index');
        } else {
            toast('Data gagal ditambahkan!', 'error');
            return redirect()->route('Ticket.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::find($id);
        return view('pages.admin.ticket.edittickets', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::find($id);
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ]);
        $dataupdate = [
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ];
        $save = $ticket->update($dataupdate);
        if ($save) {
            toast('Data berhasil diubah!', 'success');
            return redirect()->route('Ticket.index');
        } else {
            toast('Data gagal ditambahkan!', 'error');
            return redirect()->route('Ticket.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();
        toast('Data berhasil dihapus!', 'success');
        return redirect()->route('Ticket.index');
    }
    function ticketPage()
    {
        $data = Ticket::get();
        return view('ticket', compact('data'));
    }
    public function getData($id)
    {
        $data = Ticket::findOrFail($id);
        return response()->json(["data" => $data]);
    }
    function buyTicket(Request $req)
    {
        \Midtrans\Config::$serverKey = config("midtrans.server_key");
        // Jika masih development, ubah isProduction menjadi false dan ubah url snap js pada viewnya ke mode sandbox
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $id_transaction = Str::uuid();
            $data = [
                "id_transaction" => $id_transaction,
                "id_user" => Auth::user()->id,
                "payment_method" => "transfer",
                "confirmation" => 0,
                "presence" => 0,
                "id_panitia" => $req->kode_panitia,
            ];
            $searchTicket = Ticket::where(["name" => $req->nama_ticket])->first();
            $searchVoucher = Voucher::where(["kode" => $req->kode_voucher])->first();
            
            if ($searchTicket) {
                $data["id_ticket"] = $searchTicket->idTicket;
                $data["total_prices"] =  $searchTicket["price"];
            }
            if ($searchVoucher) {
                $data["id_voucher"] = $searchVoucher->id_voucher;
                $data["total_prices"] = $searchTicket["price"] - $searchVoucher["discount"];
            }
            Transaction::create($data);
            $params = array(
                "transaction_details" => array(
                    "order_id" => $id_transaction,
                    "gross_amount" => $data["total_prices"],
                ),
                "customer_details" => array(
                    "id_user" => Auth::user()->id,
                    "username" => Auth::user()->name,
                    "phone" => Auth::user()->telp
                ),
                "enabled_payment" => array('bca_va', 'bri_va', 'gopay', 'qris')
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return redirect()->route('verifikasiPembayaran', ["id" => $id_transaction, "snap" => $snapToken]);
        } catch (QueryException $e) {
            return dd($e);
        }
    } 
    // Route callback ada di api. dan belum diganti untuk payment notification url di midtransnya
    // https://youtu.be/3Wvsh7DssH4?si=O4TjRcnJJhsWxNCF (belum dilihat)
    function callback(Request $req) {
        // callback belum ditest
        $serverKey = config("midtrans.server_key");
        $hashed = hash("sha512", $req->order_id.$req->status_code.$req->gross_amount.$serverKey);
        if($hashed == $req->signature_key){
            if($req->transaction_status == "capture")
            {
                $order = Transaction::find($req->order_id);
                $order->update(["status" => "paid"]);
            }
        }
    }
    function verifikasi($id_ticket, $snapToken)
    {
        $data = Transaction::with('user', 'ticket')->where('id_transaction', '=', $id_ticket)->first();
    return view('verifikasi', compact('data', "snapToken"));
    }
}
