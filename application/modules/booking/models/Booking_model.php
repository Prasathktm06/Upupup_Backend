<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}

	public function get_details($table){
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_bookingTable($edit,$delete,$venue_id=''){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,venue_booking.*,venue.venue,venue.phone as venue_phone,users.name,users.phone_no,sports.sports,court.court,coupons.coupon_value,coupons.percentage,coupons.currency,offer.percentage as offer_percentage,venue.amount,GROUP_CONCAT(offer.offer) as offer,venue_booking.booking_id,sum(venue_booking_time.capacity) as capacity,DATE_FORMAT(venue_booking_time.date, "%d %b %Y") as booking_date,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);

		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
        $this->db->join("venue_booking_time","venue_booking.booking_id=venue_booking_time.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->from('venue_booking , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->group_by('venue_booking.booking_id');
		$this->db->order_by('venue_booking.id','desc');
		if($venue_id!='')
			$this->db->where('venue_booking.venue_id',$venue_id);
		$this->db->where('venue_booking.payment_id !=',"vendor");
		if($column==1)
		{
			$this->db->order_by('matches',$dir);
		}

		$where = "(venue.venue LIKE '%".$value."%' OR venue.phone LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' OR court.court LIKE '%".$value."%' OR coupons.coupon_value LIKE '%".$value."%' OR coupons.percentage LIKE '%".$value."%' OR coupons.currency LIKE '%".$value."%' OR offer.percentage LIKE '%".$value."%' OR venue.amount LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR venue_booking.date LIKE '%".$value."%' OR venue_booking.price LIKE '%".$value."%' OR venue_booking.payment_mode LIKE '%".$value."%' OR venue_booking.cost LIKE '%".$value."%' OR venue_booking.time LIKE '%".$value."%' OR venue_booking.bal LIKE '%".$value."%' OR venue_booking.payment_id LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' )";

		$this->db->where($where);

		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('venue_booking');
		$where = "(venue.venue LIKE '%".$value."%' OR venue.phone LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' OR court.court LIKE '%".$value."%' OR coupons.coupon_value LIKE '%".$value."%' OR coupons.percentage LIKE '%".$value."%' OR coupons.currency LIKE '%".$value."%' OR offer.percentage LIKE '%".$value."%' OR venue.amount LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR venue_booking.date LIKE '%".$value."%' OR venue_booking.price LIKE '%".$value."%' OR venue_booking.payment_mode LIKE '%".$value."%' OR venue_booking.cost LIKE '%".$value."%' OR venue_booking.time LIKE '%".$value."%' OR venue_booking.bal LIKE '%".$value."%' OR venue_booking.payment_id LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' )";

		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		if($venue_id!='')
			$this->db->where('venue_booking.venue_id',$venue_id);
                $this->db->where('venue_booking.payment_id !=',"vendor");
		$this->db->where($where);
		$query = $this->db->get();


		$this->db->from('venue_booking');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

        public function get_vendorbookingTable($edit,$delete,$venue_id=''){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,venue_booking.*,venue.venue,venue.phone as venue_phone,users.name,users.phone_no,sports.sports,court.court,coupons.coupon_value,coupons.percentage,coupons.currency,offer.percentage as offer_percentage,venue.amount,GROUP_CONCAT(offer.offer) as offer,venue_booking.booking_id,sum(venue_booking_time.capacity) as capacity,role.name as role_name,DATE_FORMAT(venue_booking_time.date, "%d %b %Y") as booking_date,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);

		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
        $this->db->join("venue_booking_time","venue_booking.booking_id=venue_booking_time.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->join("booked_manager","booked_manager.booking_id=venue_booking.booking_id","left");
		$this->db->join("user_role","user_role.user_id=booked_manager.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->from('venue_booking , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->group_by('venue_booking.booking_id');
		$this->db->order_by('venue_booking.id','desc');
		if($venue_id!='')
			$this->db->where('venue_booking.venue_id',$venue_id);
		$this->db->where('venue_booking.payment_id',"vendor");
		$this->db->where('venue_booking.payment_mode',1);
		if($column==1)
		{
			$this->db->order_by('matches',$dir);
		}

		$where = "(venue.venue LIKE '%".$value."%' OR venue.phone LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' OR court.court LIKE '%".$value."%' OR coupons.coupon_value LIKE '%".$value."%' OR coupons.percentage LIKE '%".$value."%' OR coupons.currency LIKE '%".$value."%' OR offer.percentage LIKE '%".$value."%' OR venue.amount LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR venue_booking.date LIKE '%".$value."%' OR venue_booking.price LIKE '%".$value."%' OR venue_booking.payment_mode LIKE '%".$value."%' OR venue_booking.cost LIKE '%".$value."%' OR venue_booking.time LIKE '%".$value."%' OR venue_booking.bal LIKE '%".$value."%' OR venue_booking.payment_id LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR role.name LIKE '%".$value."%' )";

		$this->db->where($where);

		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('venue_booking');
		$where = "(venue.venue LIKE '%".$value."%' OR venue.phone LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' OR court.court LIKE '%".$value."%' OR coupons.coupon_value LIKE '%".$value."%' OR coupons.percentage LIKE '%".$value."%' OR coupons.currency LIKE '%".$value."%' OR offer.percentage LIKE '%".$value."%' OR venue.amount LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR venue_booking.date LIKE '%".$value."%' OR venue_booking.price LIKE '%".$value."%' OR venue_booking.payment_mode LIKE '%".$value."%' OR venue_booking.cost LIKE '%".$value."%' OR venue_booking.time LIKE '%".$value."%' OR venue_booking.bal LIKE '%".$value."%' OR venue_booking.payment_id LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR role.name LIKE '%".$value."%')";

		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->join("booked_manager","booked_manager.booking_id=venue_booking.booking_id","left");
		$this->db->join("user_role","user_role.user_id=booked_manager.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		if($venue_id!='')
			$this->db->where('venue_booking.venue_id',$venue_id);
                $this->db->where('venue_booking.payment_id',"vendor");
		$this->db->where('venue_booking.payment_mode',1);
		$this->db->where($where);
		$query = $this->db->get();


		$this->db->from('venue_booking');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}
	 public function get_cancelbookingTable($edit,$delete,$venue_id=''){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,venue_booking.*,venue.venue,venue.phone as venue_phone,users.name,users.phone_no,sports.sports,court.court,coupons.coupon_value,coupons.percentage,coupons.currency,offer.percentage as offer_percentage,venue.amount,GROUP_CONCAT(offer.offer) as offer,venue_booking.booking_id,sum(venue_booking_time.capacity) as capacity,role.name as role_name,DATE_FORMAT(venue_booking_time.date, "%d %b %Y") as booking_date,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);

		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
        $this->db->join("venue_booking_time","venue_booking.booking_id=venue_booking_time.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->join("booking_cancel","booking_cancel.booking_id=venue_booking.booking_id","left");
		$this->db->join("user_role","user_role.user_id=booking_cancel.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->from('venue_booking , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->group_by('venue_booking.booking_id');
		$this->db->order_by('venue_booking.id','desc');
		if($venue_id!='')
			$this->db->where('venue_booking.venue_id',$venue_id);
		$this->db->where('venue_booking.payment_id',"vendor");
		$this->db->where('venue_booking.payment_mode',3);
		if($column==1)
		{
			$this->db->order_by('matches',$dir);
		}

		$where = "(venue.venue LIKE '%".$value."%' OR venue.phone LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' OR court.court LIKE '%".$value."%' OR coupons.coupon_value LIKE '%".$value."%' OR coupons.percentage LIKE '%".$value."%' OR coupons.currency LIKE '%".$value."%' OR offer.percentage LIKE '%".$value."%' OR venue.amount LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR venue_booking.date LIKE '%".$value."%' OR venue_booking.price LIKE '%".$value."%' OR venue_booking.payment_mode LIKE '%".$value."%' OR venue_booking.cost LIKE '%".$value."%' OR venue_booking.time LIKE '%".$value."%' OR venue_booking.bal LIKE '%".$value."%' OR venue_booking.payment_id LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR role.name LIKE '%".$value."%' )";

		$this->db->where($where);

		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('venue_booking');
		$where = "(venue.venue LIKE '%".$value."%' OR venue.phone LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' OR court.court LIKE '%".$value."%' OR coupons.coupon_value LIKE '%".$value."%' OR coupons.percentage LIKE '%".$value."%' OR coupons.currency LIKE '%".$value."%' OR offer.percentage LIKE '%".$value."%' OR venue.amount LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR venue_booking.date LIKE '%".$value."%' OR venue_booking.price LIKE '%".$value."%' OR venue_booking.payment_mode LIKE '%".$value."%' OR venue_booking.cost LIKE '%".$value."%' OR venue_booking.time LIKE '%".$value."%' OR venue_booking.bal LIKE '%".$value."%' OR venue_booking.payment_id LIKE '%".$value."%' OR venue_booking.booking_id LIKE '%".$value."%' OR role.name LIKE '%".$value."%')";

		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->join("booking_cancel","booking_cancel.booking_id=venue_booking.booking_id","left");
		$this->db->join("user_role","user_role.user_id=booking_cancel.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		if($venue_id!='')
			$this->db->where('venue_booking.venue_id',$venue_id);
                $this->db->where('venue_booking.payment_id',"vendor");
		$this->db->where('venue_booking.payment_mode',3);
		$this->db->where($where);
		$query = $this->db->get();


		$this->db->from('venue_booking');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}
	public function delete($id)
	{

		$this->db->delete('venue_booking',array('id'=>$id));

	}

}
