<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/verifi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/open-iconic-bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&display=swap" rel="stylesheet">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand text-white" href="index.html"><img src="{{ asset('images/aom.png') }}"
                    width="100px"></a>
           

                    <ul class="navbar-nav ">
                        <li class="nav-item ">
                            <a class="dropdown-item text-light" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
    
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>    
                        </li>
                    </ul>
            
        </div>
    </nav>
    <div class="wrapper">
        <div class="container main">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <p class="text-center m-1 "><strong>E-Ticket || Art Of Manunggalan 10.0</strong></p>
                        </div>
                        <img src="{{ asset('images/aom.png') }}" class="mx-auto d-block" width="250px" alt="AOM logo">
                        <div class="card-body">
                            <div class="container">
                                <label style="font-size: 12px;"
                                    class="col-sm-4 col-5 col-form-label text-wrap">{{ $data->created_at }}</label>

                                <div class="row">
                                    <div class="form-group row font-weight-bold ">
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap">Username</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                {{ $data->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Code
                                            Panitia</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                {{ $data->id_panitia }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap">Category</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                {{ $data->ticket->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container text-black">
                                <hr>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Harga
                                            Ticket</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                Rp. @currency($data->ticket_price)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Biaya
                                            Admin</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                Rp. @currency($data->transaction_fee)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap">Diskon</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                Rp. @currency($data->voucher_discount)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap fw-bold">Total Harga</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end fw-bold">
                                                Rp. @currency($data->total_prices)</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="form-group text-center">
                                <div class="text-center">

                                        <button id="pay-btn" class="btn btn-primary" data-bs-toggle="modal"
                                    
                                            data-bs-target="#bayarTicket"> @if (!$data->bukti_pembayaran)Bayar Sekarang @else Lihat Bukti @endif</button>
                                    


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bayarTicket" tabindex="-1" aria-labelledby="bayarTicket" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('uploadPembayaran', $data->id_transaction) }}" method="post" id="formTicket" enctype="multipart/form-data">
                @csrf
                @method("patch")
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Upload Bukti</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="staticEmail" class="col-5 col-form-label text-wrap">Bukti Pembayaran</label>
                        <div class="col-12">
                            @if(!$data->bukti_pembayaran)
                            <input type="file" class="form-control form-control-sm" name="bukti_pembayaran" onchange="previewImage(this);" readonly>
                        @endif
                        <img id="gambar-preview" src="{{ $data->bukti_pembayaran ? url('storage/'.$data->bukti_pembayaran) : '#' }}" alt="Gambar Pratinjau" 
                             style="max-width: 50%; margin: 0 auto;margin-top:10px; display: {{ $data->bukti_pembayaran ? 'block' : 'none' }};">
                        </div>
                        <br>
                        @if (!$data->bukti_pembayaran)

                        <button type="submit" class="btn btn-primary text-right">Kirim</button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        function previewImage(input) {
            var preview = document.getElementById('gambar-preview');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
</body>

</html>
