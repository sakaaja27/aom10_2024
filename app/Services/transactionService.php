<?php
namespace App\Services;

use App\Models\transaction;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserConfirmMail;
use App\Models\User;
use Midtrans\Config;
use Illuminate\Support\Str;
class transactionService{
    public function gettransactionbycodebarcodeandemail($codebarcode,$email){
        $data = transaction::with('user','ticket')->limit(1)->where('kode_barcode',$codebarcode)->orwhereHas('user', function ($query) use ($email) {
            $query->where('email', $email);
        });
            return $data;
    }
    public function gettransactionbyidandemail($idtransaction,$email){
        $data = transaction::with('user','ticket')->limit(1)->where('id_transaction',$idtransaction)->orwhereHas('user', function ($query) use ($email) {
            $query->where('email', $email);
        });
            return $data;
    }
    public function gettransactionwithmidtrans($idtransaction){
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        // dd($transactionId);
        $response = \Midtrans\Transaction::status($idtransaction);
        // dd($response);

        if ($response->status_code == 200) {
            $transactionData = [
                'status_code' => $response->status_code ?? null,
                'status_message' => $response->status_message ?? null,
                'transaction_id' => $response->transaction_id ?? null,
                'order_id' => $response->order_id ?? null,
                'gross_amount' => $response->gross_amount ?? null,
                'currency' => $response->currency ?? null,
                'payment_type' => $response->payment_type ?? null,
                'transaction_status' => $response->transaction_status ?? null,
                'signature_key' => $response->signature_key ?? null,
                'fraud_status' => $response->fraud_status ?? null,
                'merchant_id' => $response->merchant_id ?? null,
                'transaction_type' => $response->transaction_type ?? null,
                'issuer' => $response->issuer ?? null,
                'acquirer' => $response->acquirer ?? null,
                'transaction_time' => $response->transaction_time ?? null,
                'settlement_time' => $response->settlement_time ?? null,
                'expiry_time' => $response->expiry_time ?? null,
            ];
            return $transactionData;
        }

        return null;
    }
    public function gettransaction(){
        $data = transaction::with('user','ticket','voucher','panitia');
        return $data;
    }
    public function sendUserConfirmMail($confirmCode, $dataEmail)
    {
        // check confirm code
        switch ($confirmCode):
            case 1:
                $dataEmail['button'] = 'Upload Ulang';
                $dataEmail['img'] = 'img/ticket_mail/ditolak.png';
                $dataEmail['color'] = '#D61F47';
                $dataEmail['text'] = 'Ditolak';
                $dataEmail['text2'] = 'Kami mohon maaf untuk memberitahukan bahwa pembayaran tiket konser Art Of Manunggalan 10 Anda telah ditolak oleh sistem kami. Silakan hubungi tim di +62 852-1670-9554 dengan melampirkan bukti pembayaran Anda untuk menyelesaikan pembayaran dan mendapatkan tiket Anda.';
                $dataEmail['text3'] = 'Untuk Melakukan proses verifikasi Ulang pembayaran tiket konser, silakan unggah bukti pembayaran Anda di website Art Of Manunggalan 10';

                break;

            default:
            $dataEmail['img'] = 'img/ticket_mail/berhasil.png';
                $dataEmail['button'] = 'Lihat Tiket';
                $dataEmail['color'] = '#1EE0AC';
                $dataEmail['text'] = 'Berhasil';
                $dataEmail['text2'] = 'Pembayaran tiket konser Art Of Manunggalan 10 Anda telah diverifikasi oleh admin. Terima kasih atas kesabaran Anda.Silakan simpan bukti pembayaran Anda untuk berjaga-jaga. Nikmati konsernya!';
                $dataEmail['text3'] = 'Silahkan Melakukan Pengambilan Tiket di lokasi yang telah ditentukan.';
                break;
        endswitch;

        $send = Mail::to($dataEmail['email'])->send(new UserConfirmMail($dataEmail));

        return $send;
    }
    public function show($code, $role)
    {
        // convertion role
        $role = $this->userRole($role);

        // get user by code and role
        $user = User::where('role', $role)
            ->where('code', $code)
            ->first();

        return $user;
    }

    public function confirmtransaction($id, $confirmCode,$status)
    {
        $transaction = $this->gettransactionbyidandemail($id,null);
        $data = [
            'confirmation' => $confirmCode,
            'status' => $status, 
        ];
        if($confirmCode == '2')
        {
            $data["kode_barcode"] = Str::random(10);
        }
        $transaction->update($data);
        return $this->gettransactionbyidandemail($id,null);
    }

    public function presenced($user)
    {
        $user->presence = 1;
        $user->save();

        return $user;
    }
}
?>
