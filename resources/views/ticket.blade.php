<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOM 10.0</title>
    <link rel="stylesheet" href="images/aom.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand text-white" href="index.html"><img src="images/aom.png" width="100px"></a>
            <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse " id="ftco-nav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item "><a href="#" class="nav-link text-white">Art Of Manunggalan 10.0</a></li>
                </ul>

                <ul class="navbar-nav ">
                    <li class="nav-item "><a href="index.html" class="nav-link text-white">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->
    <section class="ftco-section" id="ticket">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 text-center heading-section ftco-animate">
                    <div class="text-center">
                        <div class="wm wow slideInUp animated">TICKET</div>
                        <h2 class="wow fadeInUp text-white" data-wow-delay=".2s"><span class="id-color"></span> TICKET
                        </h2>
                        <div class="small-border bg-color-2"></div>
                        <div class="spacer-single"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="carousel-testimony owl-carousel">
                        @foreach($data as $item)
                        @if($item->quantity > 0)
                        <div class="pricing-s1 mb-sm-30 wow flipInY">
                            <div class="top text-center">
                                <h2 class="ticket-name">{{$item->name}}</h2>
                            </div>
                            <div class="mid text-center bg-color-secondary text-light">
                                <p class="price">
                                    <span class="currency">Rp</span>
                                    <span class="m opt-1 hargaTiket">@currency($item->price)</span>
                                </p>
                            </div>
                            <div class="bottom">
                                <ul>
                                    <li><i class="fa-solid fa-check mr-3"></i></i>Dapat ituu ya itu dah</li>
                                    <li><i class="fa-solid fa-check mr-3"></i></i>Dapat ituu ya itu dah</li>
                                    <li><i class="fa-solid fa-check mr-3"></i></i>Dapat ituu ya itu dah</li>
                                    <li><i class="fa-solid fa-xmark mr-3"></i><s>Ga dapat ituu </s></li>
                                    <li><i class="fa-solid fa-xmark mr-3"></i><s>Ga dapat ituu</s></li>
                                </ul>
                            </div>
                            <div class="action text-center">
                                <button type="button" class="btn btn-danger btnModal"
                                style="border-radius: 40px;" data-bs-toggle="modal" data-bs-target="#modaltiket">
                                Buy Ticket
                            </button>

                            </div>
                        </div>
                        @endif
@endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>

    <form action="{{route('buyTicket')}}" method="post" enctype="multipart/form-data">
        @csrf
    <div class="modal fade" id="modaltiket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Buy Your Ticket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Transaction</h5>
                            <div class="row">
                                <div class="form-group row text-dark">
                                    <label for="staticEmail"
                                        class="col-sm-4 col-5 col-form-label text-wrap">Username</label>
                                    <div class="col-sm-8 col-7">
                                        <p class="form-control-plaintext text-end">Zhapiaa</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group row text-dark">
                                    <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Nama Ticket</label>
                                    <div class="col-sm-8 col-7">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="ticketName" name="nama_ticket" placeholder="Enter Code">
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group row text-dark">
                                    <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Code
                                        Panitia</label>
                                    <div class="col-sm-8 col-7">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm"
                                                    id="kode_panitia" name="kode_panitia" placeholder="Enter Code">
                                                <div class="input-group-append">
                                                    <button class="btn btn-danger btn-sm" type="button"
                                                        id="submitPanitia">Apply</button>
                                                </div>
                                            </div>
                                            <span id="hasilPanitia"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="input-group input-group-sm mb-3">
                                <label class="card-text text-dark">
                                    Code Voucher :
                                </label>
                            </div>
                            <input type="text" class="form-control-sm" name="kode_voucher" id="kode_voucher">
                            <span><button class="btn btn-danger" type="button" style="border-radius: 40px;"
                                    id="submitVoucher">Apply</button></span>
                        </div>
                        <span id="hasilVoucher"></span>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <strong class="card-text text-dark">
                                Rincian Pembayaran
                            </strong>
                            <div class="row">
                                <div class="form-group row text-dark">
                                    <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Harga
                                        normal</label>
                                    <div class="col-sm-8 col-7">
                                        <p class="form-control-plaintext text-end" id="hargaNormal"></p>
                                    </div>
                                </div>
                                <div class="form-group row text-dark">
                                    <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Harga
                                        Diskon</label>
                                    <div class="col-sm-8 col-7">
                                        <p class="form-control-plaintext text-end" id="hargaDiskon"></p>
                                    </div>
                                </div>
                                <div class="form-group row text-dark">
                                    <label for="staticEmail" class="col-sm-4 col-5 col-form-label text-wrap">Total
                                        Harga</label>
                                    <div class="col-sm-8 col-7">
                                        <p class="form-control-plaintext text-end" id="totalHarga"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <label for="formFile" class="form-label text-dark">Upload Pembayaran</label>
                            <input type="file" id="formFile" name="bukti_bayar" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="border-radius: 40px;"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" style="border-radius: 40px;">Buat Pesanan</button>
                </div>
            </div>
        </div>
    </div>
    </form>

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke-miterlimit="10" stroke="#F96D00" />
        </svg></div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/jquery.timepicker.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="js/google-map.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <script>
        let formatNumber = new Intl.NumberFormat("id-ID", {
            style: 'currency',
            currency: 'IDR'
        });
        $(document).ready(function() {
            var hargaTiket;
            $("#submitPanitia").click(function() {
                var idPanitia = $("#kode_panitia").val();
                $.get("getPanitia/" + idPanitia, function(item, status) {
                    if (item.data != null) {
                        $("#hasilPanitia").text("Berhasil");
                        $("#submitPanitia").hide();
                    } else {
                        alert("Kode Tidak Ditemukan");
                    }
                });
            });
            $("#submitVoucher").click(function() {
                var idVoucher = $("#kode_voucher").val();
                $.get("getVoucher/" + idVoucher, function(item, status) {
                    if (item.data != null) {
                        console.log(item.data);
                        var hargaDiskon = item.data.discount;
                        console.log(hargaTiket);
                        var totalHarga = hargaTiket - hargaDiskon;
                        $("#hargaDiskon").text(formatNumber.format(hargaDiskon));
                        $("#totalHarga").text(formatNumber.format(totalHarga));
                        $("#submitVoucher").hide();

                    } else {
                        alert("Kode Tidak Ditemukan");
                    }
                });
            });

            $(".btnModal").click(function() {
                let ticketName = $(this).closest('.pricing-s1').find(".ticket-name").html().trim();
                $("#ticketName").val(ticketName);
                $("#ticketName").prop('readonly', 'true');
                let numberString = $(this).closest('.pricing-s1').find(".hargaTiket").html().trim();
                hargaTiket = parseFloat(numberString.replace(".", ""));
                $("#hargaNormal").text(formatNumber.format(hargaTiket));
                $("#totalHarga").text(formatNumber.format(hargaTiket));
            })
        });
    </script>
</body>

</html>
