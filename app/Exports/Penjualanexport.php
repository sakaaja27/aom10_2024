<?php

namespace App\Exports;

use App\Models\Transaction;
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

    public function __construct($confirmationFilter = null, $presenceFilter = null)
    {
        $this->no = 1;
        // Modify to select all confirmation statuses and presence statuses
        $this->data = Transaction::with('user', 'ticket', 'voucher', 'panitia')->orderBy('id_transaction', 'asc');
        // Filter berdasarkan status konfirmasi
        if ($confirmationFilter == 'confirmed') {
            $this->data->where('confirmation', 2); // Hanya transaksi yang diterima
        } elseif ($confirmationFilter == 'unconfirmed') {
            $this->data->where('confirmation', 1); // Hanya transaksi yang tidak diterima
        }
        // Filter berdasarkan status kehadiran tiket
        if (!is_null($presenceFilter)) {
            $this->data->where('presence', $presenceFilter); // Kehadiran tiket sesuai filter
        }
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
            'Status Konfirmasi', // Added confirmation status
            'Kehadiran Tiket', // Added ticket presence
        ];
    }

    public function map($penjualan): array
    {
        // Mapping confirmation and presence statuses dynamically
        $confirmationStatus = $this->getConfirmationStatus($penjualan->confirmation);
        $presenceStatus = $this->getPresenceStatus($penjualan->presence);

        return [
            $this->no++,
            $penjualan->id_transaction,
            $penjualan->user->name,
            $penjualan->ticket->name,
            $penjualan->payment_method,
            $penjualan->panitia ? $penjualan->panitia->name : '-',
            $penjualan->total_prices,
            $penjualan->voucher ? $penjualan->voucher->kode : '-',
            $penjualan->bukti_transaksi, // URL or empty string
            $penjualan->created_at,
            $confirmationStatus, // Added status mapping
            $presenceStatus, // Added presence mapping
        ];
    }

    // Method to map confirmation statuses
    private function getConfirmationStatus($status)
    {
        switch ($status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Ditolak';
            case 2:
                return 'Diterima';
            default:
                return '-';
        }
    }

    // Method to map presence statuses
    private function getPresenceStatus($status)
    {
        switch ($status) {
            case 0:
                return 'Belum Diambil';
            case 1:
                return 'Sudah Diambil';
            default:
                return '-';
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header styling
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '404040'],
                    ],
                ]);

                // Adding borders to all cells
                $sheet->getStyle('A1:L' . ($this->data->count() + 1))->applyFromArray([
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
                        $sheet
                            ->getCell('I' . $rowIndex)
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
