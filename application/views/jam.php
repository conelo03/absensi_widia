<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Waktu Jam Kerja</h4>
                <div class="d-inline ml-auto float-right">
                    <a href="#" class="btn btn-success btn-sm btn-add-jam" data-toggle="modal" data-target="#add-jam" ><i class="fa fa-plus"></i> Tambah</a>
                </div>
                <!-- <p class="card-category"></p> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th>No.</th>
                            <th>Keterangan</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Divisi</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach($jam as $i => $j): ?>
                                <tr id="<?= 'jam-' . $j->id_jam ?>">
                                    <td><?= ($i+1) ?></td>
                                    <td><?= $j->keterangan ?></td>
                                    <td class="jam-start"><?= $j->start ?></td>
                                    <td class="jam-finish"><?= $j->finish ?></td>
                                    <td><?= $j->nama_divisi ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm btn-edit-jam" data-toggle="modal" data-target="#edit-jam<?= $j->id_jam ?>"><i class="fa fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-wrapper">
    <?php foreach($jam as $i => $j): ?>
    <div class="modal fade" id="edit-jam<?= $j->id_jam ?>" tabindex="-1" role="dialog" aria-labelledby="edit-jam-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?= base_url('jam/update') ?>" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-jam-label">Edit Jam <span id="edit-keterangan"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="finish">Keterangan :</label>
                            <select name="keterangan" class="form-control" required="">
                                <option disabled selected>-- Pilih Keterangan --</option>
                                <option value="Masuk" <?= $j->keterangan == 'Masuk' ? 'selected' : ''; ?>>Jam Masuk</option>
                                <option value="Pulang" <?= $j->keterangan == 'Pulang' ? 'selected' : ''; ?>>Jam Pulang</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start">Jam Mulai :</label>
                            <input type="hidden" name="id_jam" value="<?= $j->id_jam?>" >
                            <input type="time" name="start" value="<?= $j->start?>" class="form-control" placeholder="Jam Mulai" required="reuired" />
                        </div>

                        <div class="form-group">
                            <label for="finish">Jam Selesai :</label>
                            <input type="time" name="finish" value="<?= $j->finish?>" class="form-control" placeholder="Jam Selesai" required="reuired" />
                        </div>
                        <div class="form-group">
                            <label for="finish">Divisi :</label>
                            <select name="divisi" class="form-control" required="">
                                <option disabled selected>-- Pilih Divisi --</option>
                                <?php foreach ($divisi as $i => $d): ?>
                                    <option value="<?= $d->id_divisi ?>" <?= $d->id_divisi == $j->id_divisi ? 'selected' : ''; ?>><?= $d->nama_divisi ?></option>
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal-wrapper">
    <div class="modal fade" id="add-jam" tabindex="-1" role="dialog" aria-labelledby="edit-jam-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-add-jam" action="<?= base_url('jam/add') ?>" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-jam-label">Tambah Jam</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="finish">Keterangan :</label>
                            <select name="keterangan" class="form-control" required="">
                                <option disabled selected>-- Pilih Keterangan --</option>
                                <option value="Masuk">Jam Masuk</option>
                                <option value="Pulang">Jam Pulang</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start">Jam Mulai :</label>
                            <input type="hidden" name="id_jam" id="add-id-jam">
                            <input type="time" name="start" id="add-start" class="form-control" placeholder="Jam Mulai" required="reuired" />
                        </div>

                        <div class="form-group">
                            <label for="finish">Jam Selesai :</label>
                            <input type="time" name="finish" id="add-finish" class="form-control" placeholder="Jam Selesai" required="reuired" />
                        </div>
                        <div class="form-group">
                            <label for="finish">Divisi :</label>
                            <select name="divisi" class="form-control" required="">
                                <option disabled selected>-- Pilih Divisi --</option>
                                <?php foreach ($divisi as $i => $d): ?>
                                    <option value="<?= $d->id_divisi ?>"><?= $d->nama_divisi ?></option>
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

