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
            <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav mx-lg-auto">
                    <li class="nav-item "><a href="#" class="nav-link text-white">Art Of Manunggalan 10.0</a></li>
                </ul>

                <ul class="navbar-nav ">
                    <li class="nav-item "><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
											 document.getElementById('logout-form').submit();"
                            class="nav-link text-white">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <div class="container main">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <!-- <div class="card ">
              <div class="card-body">
                <p class="card-text">Your booking is being verified, please wait for the verification process, the page will change once it is verified, thank you.</p>
              </div>
            </div> -->
                    <div class="card">
                        <div class="card-header">
                            <p class="text-center m-1 "><strong>E-Ticket || Art Of Manunggalan 10.0</strong></p>
                        </div>
                        <img src="{{ asset('images/aom.png') }}" class="mx-auto d-block" width="250px" alt="AOM logo">
                        <div class="card-body">
                            <div class="container">
                                {{-- @dd($data) --}}
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
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap">Harga Ticket</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                               Rp. @currency($data->ticket_price)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap">Biaya Admin</label>
                                        <div class="col-sm-8 col-7">
                                            <p class="form-control-plaintext text-end font-weight-bold">
                                                Rp. @currency($data->midtrans_fee)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group row font-weight-bold">
                                        <label for="staticEmail"
                                            class="col-sm-4 col-5 col-form-label text-wrap">Harga Diskon</label>
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
                                    @if($data->status == "unpaid")
                                    <button id="pay-btn">Bayar Sekarang</button>
                                    @endif
                                   

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- <section class="verifikasi">
      <div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">
        
      </div>
    </section> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="{{config('midtrans.snap_url')}}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-btn');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token.
            // Also, use the embedId that you defined in the div above, here.
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* You may add your own implementation here */
                    alert("payment success!");
                },
                onPending: function(result) {
                    /* You may add your own implementation here */
                    alert("wating your payment!");
                },
                onError: function(result) {
                    /* You may add your own implementation here */
                    alert("payment failed!");
                },
                onClose: function() {
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
            });
        });
    </script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>

</html>
