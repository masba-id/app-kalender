<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kalender_model extends CI_Model{

	function get_all_events(){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->get();

		if($q->num_rows() > 0){
			$res = $q->result_array();
			return $res;
		} else {
			return NULL;
		}
	}

	function get_events_by_fulldate($fulldate){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->where('birth_date',$fulldate)
			->get();

		if($q->num_rows() > 0){
			$res = $q->result_array();
			return $res;
		} else {
			return NULL;
		}
	}

	function get_events_by_month_and_date($month_and_date){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->like('birth_date','-'.$month_and_date)
			->get();

		if($q->num_rows() > 0){
			$res = $q->result_array();
			return $res;
		} else {
			return array();
		}
	}
	
	function get_events_by_month($month){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->like('birth_date','-'.$month.'-')
			->get();

		if($q->num_rows() > 0){
			$res = $q->result_array();
			return $res;
		} else {
			return NULL;
		}
	}
	
	function get_events_by_year($year){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->like('birth_date',$year.'-')
			->get();

		if($q->num_rows() > 0){
			$res = $q->result_array();
			return $res;
		} else {
			return NULL;
		}
	}
	
	function get_events_by_name($name){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->like('name',$name)
			->get();

		if($q->num_rows() > 0){
			$res = $q->result_array();
			return $res;
		} else {
			return NULL;
		}
	}
	
	function get_event_by_id($id){
		$q = $this->db->select('*, DATE_FORMAT(`birth_date`,"%d") AS born_date, DATE_FORMAT(`birth_date`,"%m") AS born_month, YEAR(birth_date) AS born_year, YEAR(curdate()) - YEAR(`birth_date`) - (DATE_FORMAT(NOW(),"00-%m-%d") < DATE_FORMAT(`birth_date`,"00-%m-%d")) AS age')
			->from('events')
			->where('id',$id)
			->get();

		if($q->num_rows() > 0){
			$row = $q->row_array();
			return $row;
		} else {
			return NULL;
		}
	}

	function get_total_events_by_month_and_date($month_and_date){
		$q = $this->db->select('COUNT(birth_date) AS total_events')
			->from('events')
			->like('birth_date','-'.$month_and_date)
			->get();

		if($q->num_rows() > 0){
			$res = $q->row();
			return $res;
		} else {
			return NULL;
		}
	}

	function create_event(){
		$data = array(
			'name'     	 => $this->input->post('name'),
			'birth_date' => $this->input->post('birth_date')			
		);
		$this->db->insert('events',$data);
		if($this->db->affected_rows() > 0){
			return TRUE;
		}
		return FALSE;
	}

	function update_event($id){
		$kolom = trim($this->input->post('kolom'));
		$nilai = removeTags(trim($this->input->post('nilai')));
		$data = array(
			$kolom  => $nilai
		);
		$this->db->trans_start();
		$this->db->where('id',$id)->update('events', $data);
		$this->db->trans_complete();

		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		else
		{
			if ($this->db->trans_status() === false)
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
	
	function delete_event($id){
		$this->db->where('id',$id)->delete('events');
		if($this->db->affected_rows() > 0){
			return TRUE;
		}
		return FALSE;
	}

}