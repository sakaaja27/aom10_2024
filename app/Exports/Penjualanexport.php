<?php

namespace App\Exports;

use App\Models\transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Penjualanexport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $no;
    protected $data;

    public function __construct()
    {
        $this->no = 1;
        $this->data = transaction::with('user', 'ticket', 'voucher', 'panitia')->orderBy('id_transaction', 'asc');
    }

    public function collection()
    {
        return $this->data->get();
    }
    public function styles(Worksheet $sheet)
    {

        return $sheet;
    }


    public function headings(): array
    {
        return [
            'No',
            'Nomer Transaksi',
            'Nama Pembeli',
            'Tipe Tiket',
            'Metode Pembayaran',
            'Nama Panitia',
            'Jumlah Dibayar',
            'Voucher',
            'Bukti Transaksi',
            'Tanggal Transaksi',
        ];
    }

    public function map($penjualan): array
    {
        return [
            $this->no++,
            $penjualan->id_transaction,
            $penjualan->user->name,
            $penjualan->ticket->name,
            $penjualan->payment_method,
            ($penjualan->panitia) ? $penjualan->panitia->name : '-',
            $penjualan->total_prices,
            ($penjualan->voucher) ? $penjualan->voucher->kode : '-',
            $penjualan->bukti_transaksi, // URL or empty string
            $penjualan->created_at,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '404040'],
                    ],
                ]);

                // Adding border to all cells
                $sheet->getStyle('A1:J' . ($this->data->count() + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Adding hyperlinks
                foreach ($this->data->get() as $index => $penjualan) {
                    $rowIndex = $index + 2; // Start after the header row
                    $buktiTransaksi = $penjualan->bukti_transaksi;

                    if ($buktiTransaksi) {
                        $sheet->getCell('I' . $rowIndex)
                            ->setValue('Lihat Bukti')
                            ->getHyperlink()
                            ->setUrl($buktiTransaksi);

                        // Style for hyperlink text
                        $sheet->getStyle('I' . $rowIndex)->applyFromArray([
                            'font' => [
                                'color' => ['argb' => '0000FF'],
                                'underline' => Font::UNDERLINE_SINGLE,
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
