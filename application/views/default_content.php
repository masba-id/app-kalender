<div class="row">
    <div class="col-md-4">
        <?php echo $januari; ?>
    </div>
    <div class="col-md-4">
        <?php echo $februari; ?>
    </div>
    <div class="col-md-4">
        <?php echo $maret; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php echo $april; ?>
    </div>
    <div class="col-md-4">
        <?php echo $mei; ?>
    </div>
    <div class="col-md-4">
        <?php echo $juni; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php echo $juli; ?>
    </div>
    <div class="col-md-4">
        <?php echo $agustus; ?>
    </div>
    <div class="col-md-4">
        <?php echo $september; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php echo $oktober; ?>
    </div>
    <div class="col-md-4">
        <?php echo $november; ?>
    </div>
    <div class="col-md-4">
        <?php echo $desember; ?>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){

        // action when date on calendar is clicked
        $('.tabel-kalender .tag-td.day, .tabel-kalender .tag-td.currentday, .tabel-kalender .tag-td.birthday, .tabel-kalender .tag-td.holiday, .tabel-kalender .tag-td.specialdate, a.show-calendar-detail').click(function(e){
            e.stopPropagation();
            e.preventDefault();

            var tanggal_event = $(this).data('tanggal-event');
            var total_event = $(this).data('total-event');
            $('#calendarmodal').modal('toggle');

            // clear content first
            /* $('#calendarmodal .modal-header').find('#calendar-header').empty();
            $('#calendarmodal .modal-body').find('#calendar-body').empty(); */

            // set header title
            $('#calendarmodal .modal-header').find('#calendar-header').html('<h4>EVENT LIST</h4>');
            
            // set content loader
            $('#calendarmodal .modal-body').find('#calendar-body').html(img_loader + ' Please wait . . . ');
            
            // get data by using ajax
            getCalendarModal(tanggal_event, total_event);
            
            return false;
        });

    });    

</script>