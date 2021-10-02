<div class="jumbotron bg-info text-white">
    <h1 class="display-4 my-0">Sistem Informasi Absensi QR Code</h1>
    <hr class="my-4">
    <p class="lead"></p>
    <!-- <p class="lead">Pusat Pengelolaan Pendapatan Daerah Wilayah Kab. Subang</p> -->

</div>
<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Absen Ketidakhadiran Tahun <?= date('Y') ?></h6>
                
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>