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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

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
    public function buyTicket(Request $req)
{
    // Set Midtrans Configuration (Consider moving this to a config file or service provider)
    \Midtrans\Config::$serverKey = config("midtrans.server_key");
    \Midtrans\Config::$isProduction = config("midtrans.is_production");
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    $validator = Validator::make($req->all(), [
        'payment_method' => 'required|in:bri_va,bca_va,bni_va,cimb_va,permata_va,gopay_va,other_qris', // Tambahkan aturan unik di sini
        'no_telp' => 'required|numeric',
    ]);
    if($validator->fails()){
        $messages = $validator->errors()->all();
        Alert::error($messages[0])->flash();
        return back()->withErrors($validator)->withInput();
    }
    try {
        $id_transaction = Str::uuid();
        $userId = Auth::user()->id;

        // Collect request data
        $paymentMethod = $req->payment_method;
        $ticketName = $req->nama_ticket;
        $voucherCode = $req->kode_voucher;
        $panitiaId = $req->kode_panitia;

        // Initialize data array
        $data = [
            "id_transaction" => $id_transaction,
            "id_user" => $userId,
            "payment_method" => $paymentMethod,
            "no_telp" => $req->no_telp,
            "confirmation" => 0,
            "presence" => 0,
            "ticket_price" => 0,
            "voucher_discount" => 0,
            "midtrans_fee" => 0,
            "total_prices" => 0,
            "id_panitia" => $panitiaId,
        ];

        // Fetch ticket and voucher data
        $searchTicket = Ticket::where("name", $ticketName)->first();
        $searchVoucher = Voucher::where("kode", $voucherCode)->first();

        // Update data with ticket and voucher details
        if ($searchTicket) {
            $data["id_ticket"] = $searchTicket->idTicket;
            $data["ticket_price"] = $searchTicket->price;
        }

        if ($searchVoucher) {
            $data["id_voucher"] = $searchVoucher->id_voucher;
            $data["voucher_discount"] = $searchVoucher->discount;
        }

        // Calculate admin fee
        $biaya_admin = $this->calculateAdminFee($paymentMethod);

        if ($biaya_admin === null) {
            return back(); // Invalid payment method
        }

        $data["midtrans_fee"] = ($biaya_admin < 1) ? $data["ticket_price"] * $biaya_admin : $biaya_admin;
        $data["total_prices"] = $data["ticket_price"] + $data["midtrans_fee"] - $data["voucher_discount"];

        // Save transaction data
        Transaction::create($data);

        // Prepare parameters for Midtrans Snap
        $params = $this->prepareSnapParams($id_transaction, $data, $searchTicket, $searchVoucher);

        // Get Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return redirect()->route('verifikasiPembayaran', ["id" => $id_transaction, "snap" => $snapToken]);

    } catch (QueryException $e) {
        toast("Error","An error occurred while processing your request.");
        // Log the exception and return an error response
        return redirect()->back();
    }
}

/**
 * Calculate the admin fee based on payment method.
 */
private function calculateAdminFee($paymentMethod)
{
    $fees = [
        "bri_va" => 5000,
        "mandiri_va" => 5000,
        "bni_va" => 5000,
        "cimb_va" => 5000,
        "bca_va" => 5000,
        "permata_va" => 5000,
        "gopay" => 0.007,
        "other_qris" => 0.02,
    ];

    return $fees[$paymentMethod] ?? null; // Return null for unknown methods
}

/**
 * Prepare parameters for Midtrans Snap.
 */
private function prepareSnapParams($id_transaction, $data, $searchTicket, $searchVoucher)
{
    $params = [
        "transaction_details" => [
            "order_id" => $id_transaction,
            "gross_amount" => $data["total_prices"],
        ],
        "item_details" => [
            [
                "id" => $searchTicket->idTicket,
                "name" => $searchTicket->name,
                "price" => $searchTicket->price,
                "quantity" => 1,
            ],
            [
                "id" => "FEE",
                "name" => "Midtrans Fee",
                "price" => $data["midtrans_fee"],
                "quantity" => 1,
            ],
        ],
        "customer_details" => [
            "id_user" => Auth::user()->id,
            "first_name" => Auth::user()->name,
            "email" => Auth::user()->email,
            "phone" => Auth::user()->telp
        ],
        "enabled_payments" => [$data["payment_method"]],
    ];

    if ($searchVoucher) {
        $params["item_details"][] = [
            "id" => "DISC",
            "name" => "Discount",
            "price" => -$searchVoucher->discount,
            "quantity" => 1,
        ];
    }

    return $params;
}

    // Route callback ada di api. dan belum diganti untuk payment notification url di midtransnya
    // https://youtu.be/3Wvsh7DssH4?si=O4TjRcnJJhsWxNCF (belum dilihat)
    function callback(Request $req)
    {
        // callback belum ditest
        $serverKey = config("midtrans.server_key");
        $hashed = hash("sha512", $req->order_id.$req->status_code.$req->gross_amount.$serverKey);
        if ($hashed == $req->signature_key) {
            Log::info("Berhasil masuk");
            $order = Transaction::find($req->order_id);
            if ($req->transaction_status == "settlement") {
                Log::info("bener nih");
                $order->update(["status" => "paid"]);
            }else if($req->transaction_status == "pending"){
                $order->update(["status"=> "pending"]);
            }else{
                Log::error("Error nih");
            }
        }
    }
    function verifikasi($id_ticket, $snapToken)
    {
        $data = Transaction::with('user', 'ticket')->where('id_transaction', '=', $id_ticket)->first();
        return view('verifikasi', compact('data', "snapToken"));
    }
}
