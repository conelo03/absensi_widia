<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <!-- <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.ico"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <title>Absensi</title>

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" />
    
    <!-- CSS Files -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/light-bootstrap-dashboard.css?v=2.0.1') ?>" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="<?= base_url('assets/css/demo.css') ?>" rel="stylesheet" />

    <script>var BASEURL = '<?= base_url() ?>';</script>
    <?php check_absen_harian() ?>
</head>

<body>
    <div class="wrapper" style="background-color: skyblue;">
        <div class="sidebar bg-info" data-color="blue" >
            <!--
                Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

                Tip 2: you can also add an image using data-image tag
            -->
            <div class="sidebar-wrapper">
                <!-- <div class="logo">
                    <a href="<?= base_url() ?>" class="simple-text">
                        <img src="<?= base_url('assets/img/logocifo.png') ?>" alt="" class="img-fluid">
                    </a>
                </div> -->
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-white">
                            <h2 class="my-0 text-center"><label id="hours"><?= date('H') ?></label>:<label id="minutes"><?= date('i') ?></label>:<label id="seconds"><?= date('s') ?></label></h2>
                        </a>
                    </li>
                    <li class="nav-item <?= @$_active ?>">
                        <a class="nav-link" href="<?= base_url() ?>">
                            <i class="nc-icon nc-chart-pie-35"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item <?= @$_active ?>">
                        <a class="nav-link" href="<?= base_url('user') ?>">
                            <i class="nc-icon nc-circle-09"></i>
                            <p>Profil</p>
                        </a>
                    </li>
                    <?php if(is_level('Manager')): ?>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('jam') ?>">
                                <i class="nc-icon nc-time-alarm"></i>
                                <p>Jam Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('divisi') ?>">
                                <i class="nc-icon nc-bag"></i>
                                <p>Divisi</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('karyawan') ?>">
                                <i class="nc-icon nc-circle-09"></i>
                                <p>Pegawai</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/index/detail_absensi') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Absensi</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/rekap_absensi') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Rekap Absensi</p>
                            </a>
                        </li>
                        <!--<li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/index/data_izin') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Izin</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/index/data_cuti_istimewa') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Cuti Istimewa</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/index/data_cuti_biasa') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Cuti Biasa</p>
                            </a>
                        </li> 
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/laporan_absensi') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Laporan Absensi</p>
                            </a>
                        </li>-->
                    <?php else: ?>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/check_absen') ?>">
                                <i class="nc-icon nc-tag-content"></i>
                                <p class="d-inline">
                                    Absen
                                    <?php if($this->session->absen_warning == 'true'): ?>
                                        <span class="float-right ml-auto notification p-0 badge badge-danger"><i class="fa fa-exclamation"></i></span>
                                    <?php endif; ?>
                                </p>
                            </a>
                        </li>
                        <!-- <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/form_izin') ?>">
                                <i class="nc-icon nc-tag-content"></i>
                                <p>Izin</p>
                            </a>
                        </li>
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/cuti') ?>">
                                <i class="nc-icon nc-tag-content"></i>
                                <p>Cuti</p>
                            </a>
                        </li> -->
                        <li class="nav-item <?= @$_active ?>">
                            <a class="nav-link" href="<?= base_url('absensi/detail_absensi') ?>">
                                <i class="nc-icon nc-notes"></i>
                                <p>Absensi Ku</p>
                            </a>
                        </li>
                       
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard/logout') ?>">
                            <span>Log out <i class="nc-icon nc-button-power"></i></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class=" container-fluid">
                    <a class="navbar-brand" href="#pablo"> Absensi QR Code </a>
                    <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div id="alert">
                        <?php if(@$this->session->response): ?>
                            <div class="alert alert-<?= $this->session->response['status'] == 'error' ? 'danger' : $this->session->response['status'] ?> alert-dismissable fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p><?= $this->session->response['message'] ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissable fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p><?= $this->session->flashdata('success') ?></p>
                            </div>
                        <?php elseif($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissable fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p><?= $this->session->flashdata('error') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?= $content ?>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <nav>
                        <p class="copyright text-center">
                            &copy; 2021 <a href="#">Absensi SAMSAT</a>
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>
</body>

<!--   Core JS Files   -->
<script src="<?= base_url('assets/js/core/jquery.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/core/popper.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/core/bootstrap.bundle.min.js') ?>" type="text/javascript"></script>

<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="<?= base_url('assets/js/plugins/bootstrap-switch.js') ?>"></script>
<!--  Notifications Plugin    -->
<script src="<?= base_url('assets/js/plugins/bootstrap-notify.js') ?>"></script>
<!-- SweetAlert -->
<script src="<?= base_url('assets/js/plugins/sweetalert.min.js') ?>"></script>

<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="<?= base_url('assets/js/light-bootstrap-dashboard.js?v=2.0.1') ?>" type="text/javascript"></script>

<!-- Main Js -->
<script src="<?= base_url('assets/js/main.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/vendor/webcodecamjs/js/qrcodelib.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/vendor/webcodecamjs/js/webcodecamjquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/vendor/webcodecamjs/js/webcodecamjs.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/vendor/webcodecamjs/js/mainjquery.js"></script>

<!-- Custom Script -->
<script src="<?= base_url(); ?>assets/vendor/chart.js/Chart.min.js"></script>

<script type="text/javascript">
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
      // *     example: number_format(1234.56, 2, ',', ' ');
      // *     return: '1 234,56'
      number = (number + '').replace(',', '').replace(' ', '');
      var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
        };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
    }

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var dataFirst = {
        label: "Sakit",
        backgroundColor: "#4e73df",
        hoverBackgroundColor: "#194de3",
        borderColor: "#4e73df",
        data: <?= $arr_sakit ?>,
      // Set More Options
    };
       
    var dataThird = {
        label: "Alpha",
        backgroundColor: "#f54242",
        hoverBackgroundColor: "#f51111",
        borderColor: "#f54242",
        data: <?= $arr_alpha ?>,
      // Set More Options
    };

    var dataSecond = {
        label: "Izin",
        backgroundColor: "#26e036",
        hoverBackgroundColor: "#05eb18",
        borderColor: "#26e036",
        data: <?= $arr_izin ?>,

      // Set More Options
    };

    var ctx = document.getElementById("myAreaChart");
    var myBarChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= $arr_bulan ?>,
        datasets: [dataFirst, dataSecond, dataThird],
      },
      options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
          }
        },
        scales: {
          xAxes: [{
            time: {
              unit: 'month'
            },
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              maxTicksLimit: 6
            },
            maxBarThickness: 25,
          }],
          yAxes: [{
            ticks: {
              min: 0,
              max: 10,
              maxTicksLimit: 5,
              padding: 10,
              // Include a dollar sign in the ticks
              callback: function(value, index, values) {
                return number_format(value);
              }
            },
            gridLines: {
              color: "rgb(234, 236, 244)",
              zeroLineColor: "rgb(234, 236, 244)",
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2]
            }
          }],
        },
        legend: {
          display: false
        },
        tooltips: {
          titleMarginBottom: 10,
          titleFontColor: '#6e707e',
          titleFontSize: 14,
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
          callbacks: {
            label: function(tooltipItem, chart) {
              var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
              return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
            }
          }
        },
      }
    });
</script>
<script>
    var hoursLabel = document.getElementById("hours");
    var minutesLabel = document.getElementById("minutes");
    var secondsLabel = document.getElementById("seconds");
    setInterval(setTime, 1000);

    function setTime() {
        secondsLabel.innerHTML = pad(Math.floor(new Date().getSeconds()));
        minutesLabel.innerHTML = pad(Math.floor(new Date().getMinutes()));
        hoursLabel.innerHTML = pad(Math.floor(new Date().getHours()));
    }

    function pad(val) {
        var valString = val + "";
        if (valString.length < 2) {
            return "0" + valString;
        } else {
            return valString;
        }
    }

    var decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
        decoder.buildSelectMenu("select");
        decoder.play();
        /*  Without visible select menu
            decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
        */
        $('select').on('change', function(){
            decoder.stop().play();
        });

    <?php if(@$this->session->absen_needed): ?>
        var absenNeeded = '<?= json_encode($this->session->absen_needed) ?>';
        <?php $this->session->sess_unset('absen_needed') ?>
    <?php endif; ?>
</script>

</html>