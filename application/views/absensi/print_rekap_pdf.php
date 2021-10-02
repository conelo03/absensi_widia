<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Absen bulan <?= bulan($bulan) . ', ' . $tahun ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
</head>
<body>
    <div class="row mt-2">
        <div class="mt-2">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            
                        </div>
                        <div class="card-body">
                            <h3 class="text-center">DATA LAPORAN KARYAWAN</h3>
                            <br/>
                            <h5 class="card-title mb-4">Absen Bulan : <?= bulan($bulan) . ' ' . $tahun ?></h5>
                            <table class="" width="100%" border="1">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center">No</th>
                                        <th rowspan="2">Nama</th>
                                        <th rowspan="2" style="text-align: center">Divisi</th>
                                        <th rowspan="2" style="text-align: center">Total Kehadiran</th>
                                        <th colspan="3" style="text-align: center">Total Ketidakhadiran</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center">Sakit</th>
                                        <th style="text-align: center">Izin</th>
                                        <th style="text-align: center">Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $x = 1;
                                foreach($absen as $i): ?>
                                    <?php
                                        // $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';
                                    ?>
                                    <tr class="bg-default">
                                        <td style="text-align: center"><?= ($x++) ?></td>
                                        <td><?= $i[0] ?></td>
                                        <td style="text-align: center"><?= $i[1] ?></td>
                                        <td style="text-align: center"><?= $i[2] ?></td>
                                        <td style="text-align: center"><?= $i[3] ?></td>
                                        <td style="text-align: center"><?= $i[4] ?></td>
                                        <td style="text-align: center"><?= $i[5] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>