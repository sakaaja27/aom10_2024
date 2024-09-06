<?php
namespace App\Services;

use App\Models\transaction;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserConfirmMail;
use App\Models\User;

class transactionService{
    public function gettransactionbyidandemail($idtransaction,$email){
        $data = transaction::with('user','ticket')->limit(1)->where('id_transaction',$idtransaction)->where('confirmation',2)->orwhereHas('user', function ($query) use ($email) {
            $query->where('email', $email);
        });
            return $data;
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

    public function confirmtransaction($id, $confirmCode)
    {
        $transaction = $this->gettransactionbyidandemail($id,null);
        $transaction->update([
            'confirmation' => $confirmCode
        ]);
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