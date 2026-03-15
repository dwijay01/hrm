<div id="addShift" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>Tambah Shift Baru</strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?= form_open('attendance/Shift/save_shift') ?>
                                    <div class="form-group row">
                                        <label for="shift_name" class="col-sm-4 col-form-label">Nama Shift *</label>
                                        <div class="col-sm-8">
                                            <input name="shift_name" class="form-control" type="text" placeholder="e.g. Shift Pagi / Middle" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="start_time" class="col-sm-4 col-form-label">Jam Masuk (Start) *</label>
                                        <div class="col-sm-8">
                                            <input name="start_time" class="form-control" type="time" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="end_time" class="col-sm-4 col-form-label">Jam Keluar (End) *</label>
                                        <div class="col-sm-8">
                                            <input name="end_time" class="form-control" type="time" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tolerance_minutes" class="col-sm-4 col-form-label">Toleransi Telat (Menit)</label>
                                        <div class="col-sm-8">
                                            <input name="tolerance_minutes" class="form-control" type="number" value="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="status" class="form-control">
                                                <option value="1">Aktif</option>
                                                <option value="0">Non-Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="reset" class="btn btn-primary w-md m-b-5"><?= display('reset') ?></button>
                                        <button type="submit" class="btn btn-success w-md m-b-5"><?= display('save') ?></button>
                                    </div>
                                <?= form_close() ?>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd"> 
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Shift Master Setup</h4>
                </div>
                <div class="text-right">
                    <?php if($this->permission->check_label('attendance')->create()->access()): ?>
                    <button type="button" class="btn btn-primary btn-md" data-target="#addShift" data-toggle="modal">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Shift
                    </button> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table width="100%" class="datatable table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Shift</th>
                                <th>Jam Mulai Masuk</th>
                                <th>Jam Berakhir</th>
                                <th>Toleransi Keterlambatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($shifts)) { ?>
                                <?php $sl = 1; ?>
                                <?php foreach ($shifts as $shift) { ?>
                                    <tr class="<?= ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                        <td><?= $sl; ?></td>
                                        <td><?= $shift->shift_name; ?></td>
                                        <td><?= $shift->start_time; ?></td>
                                        <td><?= $shift->end_time; ?></td>
                                        <td><?= $shift->tolerance_minutes; ?> Menit</td>
                                        <td><?= ($shift->status == 1) ? 'Aktif' : 'Non-Aktif'; ?></td>
                                        <td class="center">
                                        <?php if($this->permission->check_label('attendance')->delete()->access()): ?>
                                            <a href="<?= base_url("attendance/Shift/delete_shift/$shift->shift_id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i class="fa fa-trash"></i></a> 
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $sl++; ?>
                                <?php } ?> 
                            <?php } ?> 
                        </tbody>
                    </table>  
                </div>
            </div>
        </div>
    </div>
</div>
