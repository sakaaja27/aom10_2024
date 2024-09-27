<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use App\Models\Ticket;
use App\Models\ticket_benefits;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'benefit_ticket' => 'required',
        ]);
        // dd($request->benefit_ticket);

        $create = Ticket::create($request->all());
        foreach ($request->benefit_ticket as $key => $value) {
            ticket_benefits::create([
                'id_ticket' => $create->idTicket,
                'name' => $value,
            ]);
        }
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
        $ticket = Ticket::with('ticket_benefit')->find($id);
        return view('pages.admin.ticket.edittickets', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::with('ticket_benefit')->find($id);
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
        // Delete all existing benefits for the ticket
        ticket_benefits::where('id_ticket', $ticket->idTicket)->delete();

        // Insert new benefits
        foreach ($request->benefit_ticket as $benefit) {
            ticket_benefits::create([
                'id_ticket' => $ticket->idTicket,
                'name' => $benefit,
            ]);
        }
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
    public function getData($id)
    {
        $data = Ticket::findOrFail($id);
        return response()->json(['data' => $data]);
    }
    public function buyTicket(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'payment_method' => 'required|in:bri,bca,mandiri', // Tambahkan aturan unik di sini
            'no_telp' => 'required|numeric',
        ]);
        if ($validator->fails()) {
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
                'id_transaction' => $id_transaction,
                'id_user' => $userId,
                'payment_method' => $paymentMethod,
                'no_telp' => $req->no_telp,
                'presence' => 0,
                'ticket_price' => 0,
                'voucher_discount' => 0,
                'confirmation' => 0,
                'transaction_fee' => 0,
                'total_prices' => 0,
            ];

            // Fetch ticket and voucher data
            $searchTicket = Ticket::where('name', $ticketName)->first();
            $searchVoucher = Voucher::where('kode', $voucherCode)->first();
            $searchPanitia = Panitia::where('id_panitia', $panitiaId)->first();

            // Update data with ticket and voucher details
            if ($searchTicket) {
                $data['id_ticket'] = $searchTicket->idTicket;
                $data['ticket_price'] = $searchTicket->price;
            }

            if ($searchVoucher) {
                $data['id_voucher'] = $searchVoucher->id_voucher;
                $data['voucher_discount'] = $searchVoucher->discount;
            }
            if($searchPanitia)
            {
                $data["id_panitia"] = $searchPanitia->id_panitia;
            }
            

            // Calculate admin fee
            $biaya_admin = $this->calculateAdminFee($paymentMethod);

            if ($biaya_admin === null) {
                return back(); // Invalid payment method
            }

            $data['transaction_fee'] = $biaya_admin < 1 ? $data['ticket_price'] * $biaya_admin : $biaya_admin;
            $data['total_prices'] = $data['ticket_price'] + $data['transaction_fee'] - $data['voucher_discount'];

            // Save transaction data
            Transaction::create($data);


            return redirect()->route('verifikasiPembayaran', ['id' => $id_transaction]);
        } catch (QueryException $e) {
            dd($e);
            toast('Error', 'An error occurred while processing your request.');
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
            'bri' => 2500,
            'mandiri_va' => 2500,
            'bca' => 2500,
        ];

        return $fees[$paymentMethod] ?? null; // Return null for unknown methods
    }
    public function formPembayaran(Request $req, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $req->validate([
            "bukti_pembayaran"=> 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);
        if($req->hasFile("bukti_pembayaran")){
            $transaction->update(
                [
                    "bukti_pembayaran" => $req->file("bukti_pembayaran")->store("images/bukti_pembayaran", "public"),
                ]
            );
        }
        return redirect()->back();
    }

    
    function verifikasi($id_ticket)
    {
        $data = Transaction::with('user', 'ticket')->where('id_transaction', '=', $id_ticket)->first();
        return view('verifikasi', compact('data'));
    }
}
