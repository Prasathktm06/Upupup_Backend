<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Test_model extends CI_Model{

    public function offer_timing2($offer_id,$time,$date)
    {
    		$this->db->select('offer.start_time,offer.end_time,offer.percentage,offer.amount,offer.id as offer_id');
    		$this->db->from('offer_time');
    		$this->db->join('offer','offer.id=offer_time.offer_id','left');
    		foreach ($offer_id as $key => $value) {
    		$id[]=$value['id'];
    		}
    		$this->db->where_in('offer.id', $id);
    		$this->db->where('offer.start_time <=',date('H:i:s',strtotime($time['time'])));
    		$this->db->where('offer.end_time >',date('H:i:s',strtotime($time['time'])));
    		$this->db->where('offer.start <=',$date);
    		$this->db->where('offer.end >=',$date);
    		$this->db->where('offer.status',1);
    		return $this->db->get()->result();
    }
    
    public function offer_timing($offer_id,$time,$date){
       $this->db->select('offer.start_time,offer.end_time,offer.percentage,offer.amount,offer.id as offer_id');
    		$this->db->from('offer_time');
    		$this->db->join('offer','offer.id=offer_time.offer_id','left');
    		foreach ($offer_id as $key => $value) {
    		$id[]=$value['id'];
    		}
    		$this->db->where_in('offer.id', $id);
    		$this->db->where('offer.start_time <=',date('H:i:s',strtotime($time['time'])));
    		$this->db->where('offer.end_time >',date('H:i:s',strtotime($time['time'])));
    		$this->db->where('offer.start <=',$date);
    		$this->db->where('offer.end >=',$date);
    		$this->db->where('offer.status',1);
    		return $this->db->get()->result();
    }

}