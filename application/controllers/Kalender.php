<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kalender extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Kalender_model');
        $this->tahun_berjalan = date('Y');
    }

	public function index(){
        if($this->input->is_ajax_request()):
            $nama = $this->input->post('nama');
            $hasil_cari_nama = $this->Kalender_model->get_events_by_name($nama);
            
            $data['nama'] = $nama;
            $data['search_results'] = $hasil_cari_nama;
            $view = $this->load->view('hasil_cari_nama', $data, TRUE);
            
            print $view;
        else:
            $data = array(
                'default_wrapper' => $this->load->view('default_wrapper', NULL, TRUE)
            );
        
            $this->load->view('theme', $data);
        endif;
    }

    public function load_default(){
        if($this->input->is_ajax_request()):
            //die(var_dump('ajax true'));
			$d = array(
				'januari' => $this->_bikin_kalender(1,$this->tahun_berjalan),
				'februari' => $this->_bikin_kalender(2,$this->tahun_berjalan),
				'maret' => $this->_bikin_kalender(3,$this->tahun_berjalan),
				'april' => $this->_bikin_kalender(4,$this->tahun_berjalan),
				'mei' => $this->_bikin_kalender(5,$this->tahun_berjalan),
				'juni' => $this->_bikin_kalender(6,$this->tahun_berjalan),
				'juli' => $this->_bikin_kalender(7,$this->tahun_berjalan),
				'agustus' => $this->_bikin_kalender(8,$this->tahun_berjalan),
				'september' => $this->_bikin_kalender(9,$this->tahun_berjalan),
				'oktober' => $this->_bikin_kalender(10,$this->tahun_berjalan),
				'november' => $this->_bikin_kalender(11,$this->tahun_berjalan),
				'desember' => $this->_bikin_kalender(12,$this->tahun_berjalan),
			);
			$view =  $this->load->view('default_content', $d, TRUE);
			
			print $view;
		else:
            show404();
        endif;
    }

    public function get_event_list(){
        if($this->input->is_ajax_request()):
            $bulan_dan_tanggal = $this->input->post('bulan_dan_tanggal');
            //$total_event = $this->input->post('total_event');
            $total_event = $this->Kalender_model->get_total_events_by_month_and_date($bulan_dan_tanggal);
            $list = $this->Kalender_model->get_events_by_month_and_date($bulan_dan_tanggal);
            
            $bdtArr = explode("-",$bulan_dan_tanggal);
            list($bulan, $tanggal) = $bdtArr;
            $data['bulan_dan_tanggal'] = $bulan_dan_tanggal;
            $data['total_event'] = $total_event->total_events;
            $data['tanggal_event'] = (int)$tanggal.' '.nama_bulan((int)$bulan);
            $data['list'] = $list;
            $view = $this->load->view('event_list_popup', $data, TRUE);
            
            print $view;
        else:
            show404();
        endif;
    }

    public function reload_total_events()
    {
        if($this->input->is_ajax_request()):
            $bulan_dan_tanggal = $this->input->post('bulan_dan_tanggal');
            $total_event = $this->Kalender_model->get_total_events_by_month_and_date($bulan_dan_tanggal);

            print $total_event->total_events;
        else:
            show404();
        endif;   
    }

    private function _bikin_kalender($month, $year){
        // Menciptakan array yang berisi singkatan dari hari-hari dalam satu minggu
        $HariDalamSeminggu=array('Min','Sen','Sel','Rab','Kam','Jum','Sab');
        // Hari pertama dalam bulan yang bersangkutan
        $HariPertama=mktime(0,0,0,$month,1,$year);
        // Jumlah hari pada bulan ini
        $JumlahHari=date('t',$HariPertama);
        // Mengambil beberapa informasi tentang hari pertama bulan yang bersangkutan
        $Tanggal=getdate($HariPertama);
        // Mengambil informasi tentang nama bulan
        $Bulan=$Tanggal['month'];
        // Mengambil informasi tentang tahun
        $Tahun=$Tanggal['year'];
        // Mengambil nilai indeks (0-6) hari pertama bulan bersangkutan
        $IndeksHariPertama=$Tanggal['wday'];
        // Membuat tabel tag pembuka dan header
        $Kalender="<div class='Bulan text-center'>".nama_bulan($month)."</div><div class='tag-table same-height tabel-kalender'><div class='tag-tbody'><div class='tag-tr'>";
        
        // Membuat header kalender
        foreach ($HariDalamSeminggu as $day) {
            $Kalender.="<div class='tag-th'>$day</div>";
        }

        // Tag penutup header hari kalender
        $Kalender.="</div><div class='tag-tr'>";
        
        // Inisialisasi awal counter hari, dimulai dengan 1
        $CounterHari=1;
        
        // Variabel $HariDalamSeminggu digunakan untuk memastikan bahwa kalender Tampilan terdiri dari persis 7 kolom
        if($IndeksHariPertama>0){
            for($a=0; $a<$IndeksHariPertama; $a++):
                $Kalender.="<div class='tag-td' style='background: #EEEEEE;border:0;'>&nbsp;</div>";
            endfor;	
        }

        // Menyimpan nilai bulan dengan nilai angka (01-12)
        $month=str_pad($month, 2,"0",STR_PAD_LEFT);

        while($CounterHari<=$JumlahHari) {
            // Kalau sampai kolom ke tujuh (sabtu), maka mulai baris baru
            if($IndeksHariPertama==7) {
                $IndeksHariPertama=0;
                $Kalender.="</div><div class='tag-tr'>";
            }
            $CounterHariRel=str_pad($CounterHari,2,"0",STR_PAD_LEFT);
            $date="$year-$month-$CounterHariRel";
            $hr=date("d");
            $bln=date("m");
            $thn=date("Y");
            
            $data = $this->Kalender_model->get_events_by_month_and_date($month.'-'.$CounterHariRel);
            $total_event = count($data);
            if($total_event>0 && $data[0]['born_date']==$CounterHariRel){
                $Kalender .= "<div class=\"tag-td birthday\" title=\"Hari Ulang Tahun (Klik pada tanggal untuk melihat data)\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'><a href=\"#\" class=\"show-calendar-detail\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'>$CounterHari</a></div>";
            }
            else if($CounterHari==$hr and $month==$bln and $Tahun==$thn){
                $Kalender .= "<div class=\"tag-td currentday\" title=\"Hari Ini (Klik pada tanggal untuk membuat data baru)\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'><a href=\"#\" class=\"show-calendar-detail\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'>$CounterHari</a></div>";
            }
            else if($CounterHari==17 and $month == 8){ // HUT RI
                $Kalender .= "<div class=\"tag-td specialdate\" title=\"HUT RI (Klik pada tanggal untuk membuat data baru)\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'><a href=\"#\" class=\"show-calendar-detail\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'>$CounterHari</a></div>";
            }
            else if ($IndeksHariPertama==0){
                $Kalender .= "<div class=\"tag-td holiday\" title=\"Hari Libur (Klik pada tanggal untuk membuat data baru)\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'><a href=\"#\" class=\"show-calendar-detail\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'>$CounterHari</a></div>";
            }
            else{
                $Kalender .= "<div class=\"tag-td day\" title=\"Klik pada tanggal untuk membuat data baru\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'><a href=\"#\" class=\"show-calendar-detail\" data-tanggal-event='$month-".angka_tanggal($CounterHari)."' data-total-event='$total_event'>$CounterHari</a></div>";
            }

            // Increment counters
            $CounterHari++;
            $IndeksHariPertama++;
        }
        
        // Melengkapi baris dari minggu terakhir bulan, jika diperlukan
        if($IndeksHariPertama!=7) {
            $SisaHari=7-$IndeksHariPertama;
            for($a=0; $a<$SisaHari; $a++):
                $Kalender.="<div class='tag-td' style='background: #EEEEEE;border:0;'>&nbsp;</div>";
            endfor;
        }
        $Kalender.="</div>";
        $Kalender.="</div>";
        $Kalender.="</div>";

        return $Kalender;
    }

    public function create_event(){
        if($_POST){
            if($this->Kalender_model->create_event()){
                echo "success";
            }
            else{
                echo "failed";
            }
            exit;
        }
        else{
            show404();
        }
    }

    public function delete_event(){
        if($_POST){
            if($this->Kalender_model->delete_event($this->input->post('id'))){
                echo "success";
            }
            else{
                echo "failed";
            }
            exit;
        }
        else{
            show404();
        }
    }
    
    public function update_event(){
        if($_POST){
            if($this->Kalender_model->update_event($this->input->post('id'))){
                echo "success";
            }
            else{
                echo "failed";
            }
            exit;
        }
        else{
            show404();
        }
    }

    public function get_age(){
        if($this->input->is_ajax_request()):
            $id = $this->input->post('id');
            $event_res = $this->Kalender_model->get_event_by_id($id);
            
            $age = $event_res['age'];
            print $age.' Tahun';
        else:
            print "";
        endif;
    }
    
}
