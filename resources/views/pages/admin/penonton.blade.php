@extends('layouts.admin')

@section('titlePage', 'E-Ticket | Dashboard')

@section('content')
    @push('style')
    @endpush
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Data Penonton
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Full Pagination -->
        <div class="block block-rounded">
            <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                <li class="nav-item active">
                    <button class="nav-link" id="btabswo-static-profile-tab1" data-bs-toggle="tab"
                        data-bs-target="#btabswo-static-profile1" role="tab" aria-controls="btabswo-static-profile"
                        aria-selected="false">Tiket Penonton</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabswo-static-profile-tab3" data-bs-toggle="tab"
                        data-bs-target="#btabswo-static-profile3" role="tab" aria-controls="btabswo-static-profile"
                        aria-selected="false">Penonton Hadir / sudah mengambil ticket</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabswo-static-profile-tab4" data-bs-toggle="tab"
                        data-bs-target="#btabswo-static-profile4" role="tab" aria-controls="btabswo-static-profile"
                        aria-selected="false">Pengguna Website</button>
                </li>
            </ul>
            <div class="block-content tab-content block-content-full overflow-x-auto">
                <div class="tab-pane active" id="btabswo-static-profile1" role="tabpanel"
                    aria-labelledby="btabswo-static-profile-tab" tabindex="0">
                    <h3 class="block-title">Table Konfirmasi Berhasil Pembayaran tiket</h3>
                    <table class="table table-hover table-bordered  table-vcenter js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Barcode</th>
                                <th class="d-none d-sm-table-cell">Name</th>
                                <th class="d-none d-sm-table-cell">Email</th>
                                <th class="d-none d-sm-table-cell" style="width: 5%;">Tipe Tiket</th>
                                <th class="d-none d-sm-table-cell">Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ticketsdhdibayars as $ticketsdhdibayar)
                                @php
                                    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                @endphp

                                <tr>
                                    <td class="text-center fs-sm">{{ $loop->iteration }}</td>
                                    <td class="fs-sm text-center"><img class="d-block mx-auto" width="150px"
                                            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($ticketsdhdibayar->id_transaction, $generator::TYPE_CODE_128)) }}"
                                            alt="">
                                        <small
                                            class="font-weight-bold">{{ strtoupper($ticketsdhdibayar->id_transaction) }}</small>
                                    </td>
                                    <td class="fw-semibold fs-sm">{{ $ticketsdhdibayar->user->name }}</td>
                                    <td class="fw-semibold fs-sm">{{ $ticketsdhdibayar->user->email }}</td>
                                    <td class="d-none d-sm-table-cell">
                                        {{ $ticketsdhdibayar->ticket->name }}
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <button type="button"
                                            onclick="detailtiketpengguna('{{ $ticketsdhdibayar->id_transaction }}')"
                                            class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#konfirmasipembayaran">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="btabswo-static-profile3" role="tabpanel"
                    aria-labelledby="btabswo-static-profile-tab" tabindex="0">
                    <h3 class="block-title">Table Penonton Yang sudah mengambil tiket</h3>
                    <table class="table table-hover table-bordered  table-vcenter js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="d-none d-sm-table-cell" style="width:20%;">barcode</th>
                                <th class="d-none d-sm-table-cell" style="width:20%;">nama</th>
                                <th class="d-none d-sm-table-cell" style="width: 20%;">Email</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">tanggal pembelian</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Status Pengambilan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ticketdiambils as $ticketdiambil)
                                @php
                                    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                @endphp
                                <tr>
                                    <td class="text-center fs-sm">{{ $loop->iteration }}</td>
                                    <td class="fs-sm text-center"><img class="d-block mx-auto" width="150px"
                                            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($ticketdiambil->id_transaction, $generator::TYPE_CODE_128)) }}"
                                            alt="">
                                        <small
                                            class="font-weight-bold">{{ strtoupper($ticketdiambil->id_transaction) }}</small>
                                    </td>
                                    <td class="fw-semibold fs-sm">{{ $ticketdiambil->user->name }}</td>
                                    <td class="fw-semibold fs-sm">{{ $ticketdiambil->user->email }}</td>
                                    <td class="fw-semibold fs-sm">{{ $ticketdiambil->user->created_at }}</td>
                                    <td class="fw-semibold fs-sm">
                                        @if ($ticketdiambil->presence == 1)
                                            <span
                                                class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light ">Sudah
                                                Diambil</span>
                                        @else
                                            <span
                                                class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Belum
                                                Diambil</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="tab-pane" id="btabswo-static-profile4" role="tabpanel"
                    aria-labelledby="btabswo-static-profile-tab" tabindex="0">
                    <h3 class="block-title">Table Pengguna Website Art Of Manunggalan</h3>
                    <table class="table table-hover table-bordered  table-vcenter js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="d-none d-sm-table-cell" style="width:30%;">nama</th>
                                <th class="d-none d-sm-table-cell" style="width: 30%;">Email</th>
                                <th class="d-none d-sm-table-cell" style="width: 20%;">No Telepon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penggunawebsites as $penggunawebsite)
                                @php
                                    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                @endphp
                                <tr>
                                    <td class="text-center fs-sm">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold fs-sm">{{ $penggunawebsite->name }}</td>
                                    <td class="fw-semibold fs-sm">{{ $penggunawebsite->email }}</td>
                                    <td class="fw-semibold fs-sm">{{ $penggunawebsite->telp }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- END Dynamic Table Full Pagination -->
    </div>
    <div class="modal fade" id="konfirmasipembayaran" tabindex="-1" role="dialog"
        aria-labelledby="konfirmasipembayaran" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">bukti pembayaran</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm margin mb-2">
                        ID Transaksi : <span id="id-transaksi"></span><br>
                        Nama Pembeli : <span id="nama-pembeli"></span><br>
                        Email : <span id="email"></span><br>
                        No Telepon : <span id="no-telepon"></span><br>
                        Jenis tiket : <span id="jenis-tiket"></span><br>
                        Metode Pembayaran : <span id="metode-pembayaran"></span><br>
                        Harga asli tiket : Rp.<span id="harga-asli-tiket"></span><br>
                        Uang Yang Dibayarkan : Rp.<span id="uang-dibayarkan"></span><br>
                        Link Midtrans : <a target="_blank" id="link-midtrans">Link Midtrans</a>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" data-bs-toggle="modal" class="btn btn-sm btn-secondary me-1"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function detailtiketpengguna(id) {
            $.ajax({
                url: '{{ route('tiket.penonton', ['id' => ':id']) }}'.replace(':id',
                id), // Ganti :id dengan variabel id
                type: 'GET',
                dataType: 'json',
                success: function(val) {
                    $('#id-transaksi').text(val.order_id);
                    $('#nama-pembeli').text(val.user.name);
                    $('#email').text(val.user.email);
                    $('#no-telepon').text(val.no_telp);
                    $('#jenis-tiket').text(val.ticket.name);
                    $('#metode-pembayaran').text(val.payment_method);
                    $('#harga-asli-tiket').text(val.gross_amount);
                    $('#uang-dibayarkan').text(val.total_prices);
                    $('#link-midtrans').attr('href', 'https://dashboard.midtrans.com/beta/transactions?detail='+val.transaction_id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here (optional)
                    $('#id-transaksi').text('');
                    $('#nama-pembeli').text('');
                    $('#email').text('');
                    $('#no-telepon').text('');
                    $('#jenis-tiket').text('');
                    $('#metode-pembayaran').text('');
                    $('#harga-asli-tiket').text('');
                    $('#uang-dibayarkan').text('');
                    $('#link-midtrans').attr('href', '');
                    console.error('Error submitting data:', textStatus, errorThrown);
                    return false; // Return false to indicate an error
                }
            });
        }
    </script>
    <!-- END Page Content -->
    @push('script')
        <!-- Page JS Plugins -->
        <script src="{{ asset('dashboard_assets/js/plugins/datatables/dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
        </script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}">
        </script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('dashboard_assets/js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
        <!-- Page JS Code -->
        <script src="{{ asset('dashboard_assets/js/pages/be_tables_datatables.min.js') }}"></script>
    @endpush
@endsection
