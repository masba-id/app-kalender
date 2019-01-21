<!-- Begin Modal Add Form -->
<div class="modal fade" id="addformmodal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close popup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div id="addform-header">
                    <h4>Tambah Event</h4>
                </div>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div id="addform-body">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input class="form-control" type="text" name="nama" id="nama" placeholder="Nama" required>
                            <input class="form-control" type="hidden" name="bulan_dan_tanggal" id="bulan-dan-tanggal">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <select class="form-control" id="tahun-lahir" name="tahun_lahir" required>
                                <option value="">&mdash; Pilih Tahun Lahir &mdash;</option>
                                <?php //$now=date('Y'); $i=$now-10; for($i; $i>=($now-88); $i--): ?>
                                <?php $now=date('Y'); $i=$now; for($i; $i>=1900; $i--): ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn btn-sm btn-success" onclick="jadiSimpan(this)"><span id="saving-loader"><img class="saving-loader" src="assets/images/loadericon.gif"></span><i class="glyphicon glyphicon-floppy-disk"></i> Simpan</div>
                    <button class="btn btn-sm btn-default" onclick="batalSimpan(this)" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                </div>
            </form>   
        </div>
    </div>
</div>
<!-- End Modal Add Form -->