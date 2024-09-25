<?php

namespace App\Http\Controllers;

use App\Exports\Penjualanexport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanController extends Controller
{
    public function index(){
        return view('pages.admin.laporan.indexlaporan');
    }
    public function export(Request $request){
        // dd($request);
        $confirmationFilter = $request->confirmation_filter;
        $presenceFilter = $request->presence_filter;
        $nama = ($request->nama_file != null) ? $request->nama_file : 'laporan';
        $tipefileexport = ($request->tipe_file != null) ? $request->tipe_file : 'xlsx';
        $tanggalstart = ($request->tanggal != null) ? $request->tanggal->format('Y-m-d') : date('Y-m-d');
        $namafile = $nama.'.'.$tipefileexport;
        $tipeimportfile =null;
        switch ($tipefileexport) {
            case 'xlsx':
                $tipeimportfile = \Maatwebsite\Excel\Excel::XLSX;
                break;
            case 'csv':
                $tipeimportfile = \Maatwebsite\Excel\Excel::CSV;
                break;
            case 'pdf':
                $tipeimportfile = \Maatwebsite\Excel\Excel::MPDF;
                break;
            default:
                $tipeimportfile = \Maatwebsite\Excel\Excel::XLSX;
                break;
        }
        return Excel::download(new Penjualanexport($confirmationFilter, $presenceFilter), $namafile, $tipeimportfile);
    }
    public function styles(Worksheet $sheet)
    {

    }
    public function test(){
        $data = Transaction ::with('user','ticket','voucher','panitia')->orderBy('id_transaction','asc')->get();
        dd($data);
    }
}
