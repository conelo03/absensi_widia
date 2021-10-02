<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Absensi Samsat | Login</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>">
    <script type = "text/JavaScript">
        <!--
        function AutoRefresh( t ) {
            setTimeout("location.reload(true);", t);
        }
        //-->
    </script>
</head>
<body onload = "JavaScript:AutoRefresh(10000);">
<!-- <body > -->
    <div class="wraper">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4 mx-auto">
                    <section id="login-body" class="pt-3">
                        <div class="card border-0 shadow pt-3">
                            <div class="card-header bg-transparent border-bottom-0 pb-0 text-center">
                                <img src="<?= base_url('assets/img/logologin.png') ?>" alt="Logo Indoexpress" class="img-fluid mx-auto d-block">
                                <div class="mt-3">
                                    <span class="card-info text-center">Scan untuk melakukan absensi<!-- <br/><?= $code['code'] ?> --></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <img src="<?= base_url('assets/img/'. $code['image']) ?>" alt="Logo Indoexpress" class="img-fluid mx-auto d-block">
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>
</html>