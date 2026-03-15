<div id="addRoster" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>Atur Penjadwalan Shift (Roster)</strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?= form_open('attendance/Shift/save_roster') ?>
                                    <div class="form-group row">
                                        <label for="employee_id" class="col-sm-4 col-form-label">Karyawan *</label>
                                        <div class="col-sm-8">
                                            <?php echo form_dropdown('employee_id',$employee_list,null,'class="form-control" required') ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="shift_id" class="col-sm-4 col-form-label">Pilih Shift *</label>
                                        <div class="col-sm-8">
                                            <select name="shift_id" class="form-control" required>
                                                <option value="">-- Pilih Shift --</option>
                                                <?php foreach($shift_list as $s): ?>
                                                    <option value="<?= $s['shift_id'] ?>"><?= $s['shift_name'] ?> (<?= $s['start_time'] ?> - <?= $s['end_time'] ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="start_date" class="col-sm-4 col-form-label">Dari Tanggal *</label>
                                        <div class="col-sm-8">
                                            <input name="start_date" class="form-control datepicker" type="text" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="end_date" class="col-sm-4 col-form-label">Sampai Tanggal *</label>
                                        <div class="col-sm-8">
                                            <input name="end_date" class="form-control datepicker" type="text" required>
                                            <small class="text-muted">Jadwal shift akan otomatis diisi untuk setiap hari di rentang tanggal ini.</small>
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
                    <h4>Employee Shift Roster</h4>
                </div>
                <div class="text-right">
                    <?php if($this->permission->check_label('attendance')->create()->access()): ?>
                    <button type="button" class="btn btn-primary btn-md" data-target="#addRoster" data-toggle="modal">
                        <i class="fa fa-calendar" aria-hidden="true"></i> Tambah Jadwal Karyawan
                    </button> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-body">
                <form method="get" action="<?= base_url('attendance/Shift/shift_roster') ?>" class="form-inline mb-4">
                    <div class="form-group">
                        <label>Filter Dari Tanggal:</label>
                        <input type="text" name="start_date" class="form-control datepicker" value="<?= $start_date ?>" placeholder="Start Date">
                    </div>
                    <div class="form-group">
                        <label>Sampai:</label>
                        <input type="text" name="end_date" class="form-control datepicker" value="<?= $end_date ?>" placeholder="End Date">
                    </div>
                    <button type="submit" class="btn btn-success">Tampilkan</button>
                    <hr>
                </form>

                <div class="table-responsive">
                    <table width="100%" class="datatable table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Karyawan</th>
                                <th>Tanggal Jadwal</th>
                                <th>Shift Ditugaskan</th>
                                <th>Jam Kerja Shift</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roster_data)) { ?>
                                <?php $sl = 1; ?>
                                <?php foreach ($roster_data as $roster) { ?>
                                    <tr class="<?= ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                        <td><?= $sl; ?></td>
                                        <td><?= $roster->first_name . ' ' . $roster->last_name; ?></td>
                                        <td><?= date('d M Y', strtotime($roster->roster_date)); ?></td>
                                        <td><strong><?= $roster->shift_name; ?></strong></td>
                                        <td><?= $roster->start_time; ?> - <?= $roster->end_time; ?></td>
                                        <td class="center">
                                        <?php if($this->permission->check_label('attendance')->delete()->access()): ?>
                                            <a href="<?= base_url("attendance/Shift/delete_roster/$roster->roster_id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus jadwal ini?') "><i class="fa fa-trash"></i></a> 
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $sl++; ?>
                                <?php } ?> 
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada jadwal roster pada rentang waktu ini.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>  
                </div>
            </div>
        </div>
    </div>
</div>
