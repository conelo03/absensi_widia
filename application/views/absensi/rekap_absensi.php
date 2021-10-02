<div class="row mb-2">
    <h4 class="col-xs-12 col-sm-6 mt-0">Detail Absen</h4>
    <div class="col-xs-12 col-sm-6 ml-auto text-right">
        <form action="" method="get">
            <div class="row">
                <div class="col">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="" disabled selected>-- Pilih Bulan --</option>
                        <?php foreach($all_bulan as $bn => $bt): ?>
                            <option value="<?= $bn ?>" <?= ($bn == $bulan) ? 'selected' : '' ?>><?= $bt ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col ">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="" disabled selected>-- Pilih Tahun</option>
                        <?php for($i = date('Y'); $i >= (date('Y') - 5); $i--): ?>
                            <option value="<?= $i ?>" <?= ($i == $tahun) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col ">
                    <button type="submit" class="btn btn-primary btn-fill btn-block">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="dropdown d-inline">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="droprop-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-print"></i>
                                Export Laporan
                            </button>
                            <div class="dropdown-menu" aria-labelledby="droprop-action">
                                <a href="<?= base_url('absensi/export_rekap_pdf/' . $this->uri->segment(3) . "?bulan=$bulan&tahun=$tahun") ?>" class="dropdown-item" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
                                <!-- <a href="<?= base_url('absensi/export_rekap_excel/' . $this->uri->segment(3) . "?bulan=$bulan&tahun=$tahun") ?>" class="dropdown-item" target="_blank"><i class="fa fa-file-excel-o"></i> Excel</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 ml-auto text-right mb-2">
                        
                    </div>
                </div>
            </div>            
            <div class="card-body">
                <h4 class="card-title mb-4">Absen Bulan : <?= bulan($bulan) . ' ' . $tahun ?></h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Divisi</th>
                            <th rowspan="2">Total Kehadiran</th>
                            <th colspan="3">Total Ketidakhadiran</th>
                        </tr>
                        <tr>
                            <th>Sakit</th>
                            <th>Izin</th>
                            <th>Alpha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($absen): ?>
                            <?php 
                            $x = 1;
                            foreach($absen as $i): ?>
                                <?php
                                    // $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';
                                ?>
                                <tr class="bg-default">
                                    <td><?= ($x++) ?></td>
                                    <td><?= $i[0] ?></td>
                                    <td><?= $i[1] ?></td>
                                    <td><?= $i[2] ?></td>
                                    <td><?= $i[3] ?></td>
                                    <td><?= $i[4] ?></td>
                                    <td><?= $i[5] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="bg-light" colspan="6">Tidak ada data absen</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>
