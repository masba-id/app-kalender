<?php if(!empty($list)): ?>
	<div class="alert alert-info">
        <h4>Daftar event pada tanggal "<b><?=$tanggal_event?></b>":</h4>
    </div>
	<div class="table-responsive">
        <p class="popup-notice">Jumlah: <b><span id="person-count"><?=$total_event?></span> orang</b></p><br>
        <p class="popup-notice">NB: Silakan klik pada data <b>Nama</b> atau <b>Tanggal Lahir</b> jika ingin merubahnya</p>
        <a id="btn-add-event" href="#"><span class="btn-add-event"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Event</span></a>
		<table class="table table-bordered" id="event-list-table">
	        <tr>
	            <th>ID</th>
	            <th style="width: 30%;">Nama</th>
	            <th style="width: 40%;">Tanggal Lahir (YYYY-MM-DD)</th>
	            <th>Usia</th>
	            <th>Hapus</th>
	        </tr>
	        <?php $i=1; foreach ($list as $l): ?>
				<tr data-id="<?=$l['id']?>">
					<td><?=$l['id']?></td>
					<td class="editable-name" contenteditable="true" onBlur="saveToDatabase2(this,'name','<?=$l['id']?>')" onClick="showEditName2(this);">
                        <?=$l['name']?>
                    </td>
                    <td class="editable-birth-date">
                        <input class="mydatepicker" type="text" value="<?=$l['birth_date']?>" onBlur="saveDateToDatabase2(this,'birth_date','<?=$l['id']?>')" onClick="showEditDate2(this,'<?=$l['id']?>');">
                        <input type="hidden" id="birth-date-popup-for-<?=$l['id']?>">
                    </td>
					<td id="age-popup-for-<?=$l['id']?>"><?=$l['age']?> Tahun</td>
					<td><i class="glyphicon glyphicon-trash delete" title="Delete"></i></td>
				</tr>
			<?php $i++; endforeach; ?>
	    </table>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $('.editable-name').on('keypress', function (e) {
                if(e.which === 13){
                    $(this).blur();
                }
            });
            $('.mydatepicker').mask('0000-00-00');
            $('.mydatepicker').focus(function(){
                $(this).attr('readonly',true);
            });

        });

        $('.delete').click(function(e){
            e.preventDefault();
            var row = $(this).parent().parent();
            var id = row.data('id');

            if(confirm('Apakah anda yakin ingin menghapus item ini?')){
                $.ajax({
                    url: 'kalender/delete_event/',
                    type: "POST",
                    dataType: "html",
                    data: "id="+id,
                    success: function(response){
                        if(response === 'success'){
                            row.fadeOut('fast');
                            $.ajax({
                                url: 'kalender/reload_total_events',
                                type: "POST",
                                data: {bulan_dan_tanggal: '<?=$bulan_dan_tanggal?>'},
                                success: function(response, textStatus, jqXHR){
                                    $('#person-count').html(response);
                                    reloadCalendar();
                                    console.log(textStatus);
                                },
                                error: function(){
                                    //
                                }
                            });
                        }else{
                            alert("Hapus gagal!");
                        }
                        console.log(response);
                    },
                    error: function(){
                        alert('Error occured on AJAX request! Please try again later.');
                    }
                });
            }else{
                return false;
            }
            return false;
        });

        function showEditName2(editableObj){
            $(editableObj).css("background","#EEEEEE");
        } 

        function showEditDate2(editableObj, id){
            $(editableObj).css("background","#EEEEEE");
            $(editableObj).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    $(editableObj).parent().find('#birth-date-popup-for-'+id).val(dateText);
                    saveDateToDatabase2(editableObj, 'birth_date', id);
                }
            }).datepicker('show'); 
        } 

        function saveToDatabase2(editableObj, column, id){
            $(editableObj).css("background","#FFF url(assets/images/loadericon.gif) no-repeat right");
            var v = editableObj.innerHTML.trim();
            $.ajax({
                url: 'kalender/update_event/',
                type: "POST",
                data: {id: id, kolom: column, nilai: v},
                success: function(response){
                    if(response === 'success'){
                        $(editableObj).css("background","#FFF");
                    }else{
                        $(editableObj).css("background","#FFCDD2 url(assets/images/failed.png) no-repeat right");
                    }
                    console.log(response);
                },
                error: function(){
                    alert('Error occured on AJAX request! Please try again later.');
                }
            });
        }

        function saveDateToDatabase2(editableObj, column, id){
            $(editableObj).css("background","#FFF url(assets/images/loadericon.gif) no-repeat right");
            var v = $(editableObj).parent().find('#birth-date-popup-for-'+id).val();
            if(v != ""){
                $.ajax({
                    url: 'kalender/update_event/',
                    type: "POST",
                    data: {id: id, kolom: column, nilai: v},
                    success: function(response){
                        if(response === 'success'){
                            $(editableObj).css("background","#FFF");
                            if(v != ""){
                                updateAge2(id);
                            }
                        }else{
                            $(editableObj).css("background","#FFCDD2 url(assets/images/failed.png) no-repeat right");
                        }
                        console.log(response);
                    },
                    error: function(){
                        alert('Error occured on AJAX request! Please try again later.');
                    }
                });
            }
            else{
                $(editableObj).css("background","#FFF");
            }
        }

        function updateAge2(id){
            var age_content = $('#age-popup-for-'+id);
            age_content.css("background","#FFF url(assets/images/loadericon.gif) no-repeat right");
            $.ajax({
                url: 'kalender/get_age/',
                type: "POST",
                data: {id: id},
                success: function(response){
                    if(response !== ''){
                        age_content.css("background","#FFF");
                        age_content.html(response)
                    }else{
                        age_content.css("background","#FFCDD2 url(assets/images/failed.png) no-repeat right");
                    }
                    console.log(response);
                },
                error: function(){
                    alert('Error occured on AJAX request! Please try again later.');
                }
            });
        }
    </script>
<?php else: ?>
    <p class="popup-notice">Belum ada data untuk tanggal "<b><?=$tanggal_event?></b>"</p><br>
    <p class="popup-notice">Silakan klik tombol <i class="glyphicon glyphicon-plus-sign"></i> Tambah Event untuk membuat data baru</p>
    <a id="btn-add-event" href="#"><span class="btn-add-event"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Event</span></a>
<?php endif; ?>

<script>
    
    $('#btn-add-event').click(function(e){
        e.preventDefault();
        
        showAddForm();
    });
    
    function showAddForm() {
        // show second modal
        $('#addformmodal').modal('toggle');
    
        // set value to hidden element in second modal
        var bdt = $('#addformmodal').find('#bulan-dan-tanggal');
        bdt.val('<?=$bulan_dan_tanggal?>');
    }
</script>
