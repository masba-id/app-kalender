<?php if(!empty($search_results)): ?>
	<div class="alert alert-success">
        <h4>Hasil pencarian nama "<b><?=$nama?></b>":</h4>
    </div>
	<div class="table-responsive">
        <p>NB: Silakan klik pada data <b>Nama</b> atau <b>Tanggal Lahir</b> jika ingin merubahnya</p>
		<table class="table table-bordered" id="search-results-table">
	        <tr>
	            <th>No</th>
	            <th style="width: 30%;">Nama</th>
	            <th style="width: 40%;">Tanggal Lahir (YYYY-MM-DD)</th>
	            <th>Usia</th>
	            <th>Hapus</th>
	        </tr>
	        <?php $i=1; foreach ($search_results as $s): ?>
				<tr data-id="<?=$s['id']?>">
					<td><?=$i?></td>
					<td class="editable-name" contenteditable="true" onBlur="saveToDatabase(this,'name','<?=$s['id']?>')" onClick="showEditName(this);">
                        <?=$s['name']?>
                    </td>
                    <td class="editable-birth-date">
                        <input class="mydatepicker" type="text" value="<?=$s['birth_date']?>" onBlur="saveDateToDatabase(this,'birth_date','<?=$s['id']?>')" onClick="showEditDate(this,'<?=$s['id']?>');">
                        <input type="hidden" id="birth-date-for-<?=$s['id']?>">
                    </td>
					<td id="age-for-<?=$s['id']?>"><?=$s['age']?> Tahun</td>
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

        function showEditName(editableObj){
            $(editableObj).css("background","#EEEEEE");
		} 
        
        function showEditDate(editableObj, id){
            $(editableObj).css("background","#EEEEEE");
            $(editableObj).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    $(editableObj).parent().find('#birth-date-for-'+id).val(dateText);
                    saveDateToDatabase(editableObj, 'birth_date', id);
                }
            }).datepicker('show'); 
		} 

        function saveToDatabase(editableObj, column, id){
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
        
        function saveDateToDatabase(editableObj, column, id){
            $(editableObj).css("background","#FFF url(assets/images/loadericon.gif) no-repeat right");
            var v = $(editableObj).parent().find('#birth-date-for-'+id).val();
            if(v != ""){
                $.ajax({
                    url: 'kalender/update_event/',
                    type: "POST",
                    data: {id: id, kolom: column, nilai: v},
                    success: function(response){
                        if(response === 'success'){
                            $(editableObj).css("background","#FFF");
                            if(v != ""){
                                updateAge(id);
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

        function updateAge(id){
            var age_content = $('#age-for-'+id);
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
<?php endif; ?>