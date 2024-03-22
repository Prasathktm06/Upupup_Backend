<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Venue extends REST_Controller
{

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/venue_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");
	}

	public function index_post()
	{
		$user_id = $this->input->post('user_id');
		$venue_id = $this->input->post('venue_id');
		$sports = $this->input->post('sports');
		$area = $this->input->post('area');

		// $sports = $_GET['sports'];
		// $area = $_GET['area'];
		if ($user_id)
			$location = $this->venue_model->get_user_location($user_id);
		else
			$location = 0;

		$data = $this->venue_model->get_venue($user_id, $venue_id, $sports, $area, $location);
		if (!empty($data)) {

			foreach ($data as $key => $value) {
				$value->venue_sports_image = $this->venue_model->venue_sports_images($value->id)->venue_sports_image;
				$value->gallery_image = $this->venue_model->venue_images($value->id);
				$offer = $this->venue_model->get_venue_offer($value->id);

				if ($offer) {
					$value->offer_status = $offer->status;
					$value->percentage = $offer->percentage;
					$value->offer_amount = $offer->offer_amount;
				} else {
					$value->offer_status = '';
					$value->percentage = '';
					$value->offer_amount = '';
				}
			}

			$sum = 0;
			$sports = [];
			$venue_sports = null;
			foreach ($data as $key => $val) {
				$val->notification_id = '';
				$val->facility = explode(',', $val->facility);
				$rating = $this->venue_model->get_venue_rating($val->id);
				if ($rating) {
					$rating = explode(',', $rating->rating);
					$val->rating = round(array_sum($rating) / count($rating));
				} else {
					$val->rating = 0;
				}
			}

			foreach ($data as $key => $value) {

				$result = $this->venue_model->get_venue_court($value->id);
				$value->venue_sports = explode(',', $value->venue_sports);
				$value->venue_sports_id = explode(',', $value->venue_sports_id);
				$value->venue_sports_image = explode(',', $value->venue_sports_image);

				if (!empty($result)) {

					if ($value->id == $user_id) {
						$value->user_status = true;
					} else {
						$value->user_status = false;
					}
					$value->court = explode(',', $result->court);
					$value->court_id = explode(',', $result->court_id);
					$value->court_cost = explode(',', $result->court_cost);
					$value->sports_id = explode(',', $result->sports_id);
					$value->sports = explode(',', $result->sports);
					$value->sports_image = explode(',', $result->image);
					$value->book_status = $value->book_status;
				} else {
					$value->book_status = $value->book_status;
					$value->court = array();
					$value->court_id = array();
					$value->court_cost = array();
					$value->sports_id = array();
					$value->sports_image = array();
					$value->notification = '';
				}
			}

			$court = array();
			foreach ($data as $key => $value) {
				foreach ($value->court as $key2 => $value2) {
					$court_cst = (int) $value->court_cost[$key2];
				}


				if ($value->percentage == 0 && $value->offer_amount == 0) {
					$value->percentage2 = $value->percentage . "%";
				} else {
					if ($value->percentage != 0 && $value->offer_amount == 0) {
						$value->percentage2 = $value->percentage . "%";
					} else {
						if ($value->percentage == 0 && $value->offer_amount != 0) {
							$value->percentage2 = "RS " . $value->offer_amount;
						} else {
							if ($value->percentage != 0 && $value->offer_amount != 0) {
								$offer_pts = $court_cst * (int) $value->percentage / 100;
								$offer_amt = (int) $value->offer_amount;
								if ($offer_pts > $offer_amt) {
									$value->percentage2 = $value->percentage . "%";

								} else {

									$value->percentage2 = "RS " . $value->offer_amount;
								}

							}

						}

					}

				}
				if ($value->image == null)
					$value->venue_image = array();
				else
					$value->venue_image = array($value->image);
				
				foreach ($value->court as $key2 => $value2) {

					if ($value->percentage == 0 && $value->offer_amount == 0) {
						$offer_price = $value->court_cost[$key2];
					} else {
						if ($value->percentage != 0 && $value->offer_amount == 0) {
							$offer_price = (int) $value->court_cost[$key2] * (int) $value->percentage / 100;
							$offer_price = (int) $value->court_cost[$key2] - $offer_price;
						} else {
							if ($value->percentage == 0 && $value->offer_amount != 0) {
								$offer_price = (int) $value->court_cost[$key2] - $value->offer_amount;
							} else {
								if ($value->percentage != 0 && $value->offer_amount != 0) {
									$offer_price = (int) $value->court_cost[$key2] * (int) $value->percentage / 100;
									$offer_amounts = $value->offer_amount;
									if ($offer_price > $offer_amounts) {
										$offer_price = (int) $value->court_cost[$key2] * (int) $value->percentage / 100;
										$offer_price = (int) $value->court_cost[$key2] - $offer_price;
									} else {
										$offer_price = (int) $value->court_cost[$key2] - $value->offer_amount;
									}

								}

							}

						}

					}

					$offer_price = number_format((float) $offer_price, 2, '.', '');
					$sports[] = array(
									'sports_id' => $value->sports_id[$key2],
									'sports' => $value->sports[$key2],
									'image' => $value->sports_image[$key2]

									);
					$sports = array($sports[count($sports) - 1]);
					$court[] = array(
									'court_id' => $value->court_id[$key2],
									'court' => $value2,
									'offer_price' => $offer_price,
									'cost' => $value->court_cost[$key2],
									'offer' => $value->percentage2,
									'sports' => $sports
								);
				}

				foreach ($value->venue_sports as $key3 => $value3) {
					$venue_sports[] = array(
						'sports_id' => $value->venue_sports_id[$key3],
						'sports' => $value3,
						'image' => $value->venue_sports_image[$key3]

					);
				}

				unset($value->sports_id, $value->sports, $value->court, $value->court_cost, $value->court_id, $value->sports_image, $value->percentage, $value->image);
				$value->court = $court;
				$value->venue_sports_2 = ($venue_sports ? $venue_sports : array());
				$court = array();
				$venue_sports = array();
				$sports = array();
			}

			if (!empty($data)) {
				$result = array(
							'ErrorCode' => 0,
							'Data' => $data,
							'Message' => ""
						);

			} else {
				$result = array(
							'ErrorCode' => 1,
							'Data' => '',
							'Message' => "No Data Found"
						);

			}
			return $this->response($result, 200);
		} else {
			$result = array(
						'ErrorCode' => 1,
						'Data' => '',
						'Message' => "No Data Found"
					);
			return $this->response($result, 200);
		}
	}

	public function rate_post()
	{
		if ($this->input->post('user_id') && $this->input->post('venue_id') && $this->input->post('booking_id')) {
			$user_id = $this->input->post('user_id');
			$venue_id = $this->input->post('venue_id');
			$booking = $this->input->post('booking_id');
			$rate = $this->input->post('rate');
			$reason = $this->input->post('reason');
			$check = $this->venue_model->get_booking_id($user_id, $venue_id, $booking);
			//print_r($check);exit;
			if (empty($check)) {

				$data = array(
					'user_id' => $user_id,
					'venue_id' => $venue_id,
					'booking_id' => $booking,
					'rating' => $rate,
					'reason' => $reason
				);
				$this->venue_model->rate($data);

				$result = array(
					'ErrorCode' => 0,
					'Data' => "",
					'Message' => "Success"
				);
			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => '',
					'Message' => "Rating Failed"
				);
			}

			return $this->response($result, 200);
		} else {
			$result = array(
				'ErrorCode' => 1,
				'Data' => '',
				'Message' => "Fields Missing"
			);
			return $this->response($result, 200);
		}
	}

	public function court_post()
	{
		$user_id = $this->input->post('user_id');
		$court_id = $this->input->post('court_id');
		$data = $this->venue_model->court_time($court_id);

	}
	public function court_time_post()
	{
		$court_id = $this->input->post('court_id');
		//$venue_id=$this->input->post('venue_id');

		$data = $this->venue_model->get_court_time($court_id);
		$result = array(
			'ErrorCode' => 0,
			'Data' => $data,
			'Message' => ""
		);
		return $this->response($result, 200);
	}
	/////////////////////////////Court Timing////////////////////////////Amr

	/////////////////////////////////////////////////////////////////////////////

	public function court_timing_post()
	{

		if ($this->input->post('court_id') && $this->input->post('day') && $this->input->post('month') && $this->input->post('year') && $this->input->post('venue_id')) {
			$court_id = $this->input->post('court_id');
			$day = $this->input->post('day');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$venue_id = $this->input->post('venue_id');
			$date = date($year . "-" . $month . "-" . $day);

			$week = date('l', strtotime($date));
			$nameOfDay = date('D', strtotime($date));

			$court_timing = $this->venue_model->court_time($court_id, $week, $date);
			$court_capacity = $this->venue_model->get_court($court_id)[0]->capacity;
			//print_r($court_capacity);exit;
			$offer = $this->venue_model->court_offer($court_id, $date, $nameOfDay);
			$inactive = $this->venue_model->court_inactive($court_id, $date, $nameOfDay);
			//print_r($inactive);exit;
			$holiday = $this->venue_model->is_holiday2($date, $venue_id);
			//print_r($holiday);exit;

			if (empty($holiday)) {
				$i = 0;
				//  print_r($court_timing);exit;
				foreach ($court_timing as $key => $value) {




					if ($value->time != "Holiday") {


						$value->slots[$i] = array('time' => date('h:i A', strtotime($value->time)));
						$value->slots[$i]['id'] = $value->id;
						$value->slots[$i]['intervel'] = $value->intervel;
						$value->slots[$i]['court'] = $value->court;

						$slot_id = $value->slot_id;
						$court_id = $value->id;
						$hot_offer = $this->venue_model->get_hot_slot($court_id, $slot_id, $date);

						if (!empty($hot_offer)) {
							foreach ($hot_offer as $key => $value1) {
								$hot_offer_id = $value1->id;
								$hot_offer_percentage = $value1->precentage;
							}
							$value->slots[$i]['isOffer'] = "True";
							$value->slots[$i]['offer_type'] = 2;
							$value->slots[$i]['percentage'] = $hot_offer_percentage;
							$offer_price = ($hot_offer_percentage / 100) * $value->cost;
							$value->slots[$i]['offer_price'] = $value->cost - $offer_price;
							$value->slots[$i]['offer_id'] = $hot_offer_id;

						} else {
							if (!empty($offer)) {

								$offer_timing = $this->venue_model->offer_timing2($offer, $value->slots[$i], $date);

								if (!empty($offer_timing)) {


									if ($offer_timing[0]->amount == 0) {

										$value->slots[$i]['isOffer'] = "True";
										$value->slots[$i]['offer_type'] = 1;
										$value->slots[$i]['percentage'] = $offer_timing[0]->percentage;
										$offer_price = ($offer_timing[0]->percentage / 100) * $value->cost;
										$value->slots[$i]['offer_price'] = $value->cost - $offer_price;
										$value->slots[$i]['offer_id'] = $offer_timing[0]->offer_id;

									} else {

										$value->slots[$i]['isOffer'] = "False";
										$value->slots[$i]['offer_type'] = 0;
										$value->slots[$i]['percentage'] = '';
										$value->slots[$i]['offer_price'] = '';
										$value->slots[$i]['offer_id'] = '';

									}

								} else {
									$value->slots[$i]['isOffer'] = "False";
									$value->slots[$i]['offer_type'] = 0;
									$value->slots[$i]['percentage'] = '';
									$value->slots[$i]['offer_price'] = '';
									$value->slots[$i]['offer_id'] = '';
								}

							} else {
								$value->slots[$i]['isOffer'] = "False";
								$value->slots[$i]['offer_type'] = 0;
								$value->slots[$i]['percentage'] = '';
								$value->slots[$i]['offer_price'] = '';
								$value->slots[$i]['offer_id'] = '';
							}
						}


						$court_booked = $this->venue_model->court_booked2($court_id, end($value->slots), $date, $value->capacity);


						if (!empty($court_booked)) {

							$value->slots[$i]['isBooked'] = "True";

							$value->slots[$i]['remaining_capacity'] = 0;

						} else {
							if ($date == date('Y-m-d')) {
								if (strtotime($value->slots[$i]['time']) < time()) {

									$value->slots[$i]['isBooked'] = "True";

									$value->slots[$i]['remaining_capacity'] = 0;

								} else {
									$value->slots[$i]['isBooked'] = "False";
									$res = $this->venue_model->get_court_book($value->slots[$i]['time'], $court_id, $date, $court_capacity);


									// if(!empty($res))
									// 	$value->slots[$i]['isBooked']="True";
									$res = $this->venue_model->court_booked2($court_id, end($value->slots), $date);

									if (empty($res))
										$remaining_capacity = $value->capacity;
									else
										$remaining_capacity = $value->capacity - $res[0]['remaining_capacity'] - @$this->venue_model->get_court_book_capacity($court_id, $value->slots[$i]['time'], $date)->capacity;


									$value->slots[$i]['remaining_capacity'] = $remaining_capacity;
								}
							} else {

								$value->slots[$i]['isBooked'] = "False";

								$res = $this->venue_model->get_court_book($value->slots[$i]['time'], $court_id, $date, $court_capacity);

								// if(!empty($res))
								// 	$value->slots[$i]['isBooked']="True";
								$res = $this->venue_model->court_booked2($court_id, end($value->slots), $date);
								if (empty($res))
									$remaining_capacity = $value->capacity;
								else
									$remaining_capacity = $value->capacity - $res[0]['remaining_capacity'] - @$this->venue_model->get_court_book_capacity($court_id, $value->slots[$i]['time'], $date)->capacity;



								$value->slots[$i]['remaining_capacity'] = $remaining_capacity;
							}

						}
						if (!empty($inactive)) {

							$inactive_timing = $this->venue_model->inactive_timing2($inactive, $value->slots[$i], $date);

							if (!empty($inactive_timing)) {

								$value->slots[$i]['isInactive'] = "True";


							} else {
								$value->slots[$i]['isInactive'] = "False";
							}

						} else {
							$value->slots[$i]['isInactive'] = "False";
						}

						$i += 1;
					}
					unset($value->id, $value->time, $value->week, $value->cost, $value->timing, $value->court_id, $value->court, $value->intervel, $value->court_time_id, $value->capacity);

				}
				$court[0] = [];
				foreach ($court_timing as $key => $value) {
					foreach ($value as $key2 => $value2) {
						foreach ($value2 as $key3 => $value3) {
							$court[0][] = $value3;
						}
					}
				}
				//print_r($court_capacity);exit;

				$result = array(
					'ErrorCode' => 0,
					'Data' => $court[0],
					'total_capacity' => $court_capacity,
					'Message' => ""
				);

			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => "",
					'Message' => "Holiday"
				);
			}
			return $this->response($result, 200);
		} else {
			$result = array(
				'ErrorCode' => 1,
				'Data' => "",
				'Message' => "Fields Missing"
			);
			return $this->response($result, 200);
		}

	}



	public function booking_post()
	{

		$user_id = $this->input->post('user_id');
		$sports_id = $this->input->post('sports_id');
		$date = $this->input->post('date');
		$court_id = $this->input->post('court_id');
		$venue_id = $this->input->post('venue_id');
		$co_players = json_decode($this->input->post('co_players'));
		$co_players_contact = json_decode($this->input->post('co_players_contact'));
		$court_time = json_decode($this->input->post('court_time'));
		$cap = json_decode($this->input->post('capacity'));
		$coupon_id = $this->input->post('coupon_id');
		$offer = $this->input->post('offer');
		$price = $this->input->post('price');
		$cost = $this->input->post('cost');
		$balance = $this->input->post('balance');
		$mode = $this->input->post('mode');
		$offer_id = json_decode($this->input->post('offer_id'));
		$check = 0;


		$capacity = $this->venue_model->get_court($court_id)[0]->capacity;

		foreach ($court_time as $key => $value) {

			$status = $this->venue_model->check_court_booked($court_id, array('time' => $value), $date, $capacity);
			//print_r($bookcap_sum);exit;
			if (!empty($status)) {
				foreach ($status as $row) {
					$bookcap_sum = $row->sum;
				}

				$available_capa = $capacity - $bookcap_sum;
				//print_r($bookcap_sum);exit;
				if ($available_capa < $cap[$key]) {
					$check = 1;
				}
			}


		}
		//

		if ($check == 0) {

			$booking_id = new DateTime();
			$booking_id = $user_id + $booking_id->getTimestamp();

			$data = array(
				'user_id' => $user_id,
				'sports_id' => $sports_id,
				'date' => $date,
				'court_id' => $court_id,
				'venue_id' => $venue_id,
				'cost' => $cost,
				'booking_id' => $booking_id,
				'payment_mode' => '2',
				'coupon_id' => '0',
				'offer_value' => $offer,
				'price' => $price,
				'bal' => $balance,
				'time' => date('Y-m-d h:i:sa')
			);

			$add = $this->venue_model->add_booking('venue_booking', $data);
			/*
					foreach ($offer_id as $key => $value) {
						$data=array(
						'booking_id'=>$booking_id,
						'offer_id'=>$value,
						);
						$this->venue_model->add_booking('booking_offer',$data);
					}
					*/
			foreach ($offer_id as $key => $value1) {
				$offer_id = $value1->offer_id;
				$offer_type = $value1->offer_type;

				if ($offer_type == 1) {
					$data = array(
						'booking_id' => $booking_id,
						'offer_id' => $offer_id,
					);
					$this->venue_model->add_booking('booking_offer', $data);
				}

				if ($offer_type == 2) {
					$data = array(
						'booking_id' => $booking_id,
						'hot_offer_id' => $offer_id,
					);
					$this->venue_model->add_booking('booking_hot_offer', $data);
				}
			}

			foreach ($court_time as $key => $value) {
				$data = array(
					'booking_id' => $booking_id,
					'court_time' => date("H:i:s", strtotime($value)),
					'court_id' => $court_id,
					'capacity' => $cap[$key],
					'date' => $date
				);
				$this->venue_model->add_booking('venue_booking_time', $data);
			}
			if ($add) {
				if (!empty($co_players)) {

					foreach ($co_players as $key => $value) {
						$data = array(
							'booking_id' => $booking_id,
							'court_id' => $court_id,
							'date' => $date,
							'user_id' => $value

						);
						$this->venue_model->add_booking('venue_players', $data);

						$co_player_id = $value;
						$coplayer_sports = $this->venue_model->get_coplayer_sports($sports_id, $co_player_id);
						if (!empty($coplayer_sports)) {

							$data3 = array(
								'user_id' => $user_id,
								'co_player' => $value,
								'sports_id' => $sports_id,
								'rating' => 0
							);
							$this->venue_model->insert_coplayer($data3);

						}



					}
					//$output = $this->sampling($co_players, 2,$booking_id);
				}

				if (!empty($co_players_contact)) {

					foreach ($co_players_contact as $key => $value1) {
						$contact_name = $value1->contact_name;
						$contact_number = $value1->contact_number;

						$user_check = $this->venue_model->get_usercheck($contact_number);

						if (!empty($user_check)) {

							foreach ($user_check as $key => $value2) {
								$existing_user = $value2->id;
							}

							$data = array(
								'booking_id' => $booking_id,
								'court_id' => $court_id,
								'date' => $date,
								'user_id' => $existing_user

							);
							$this->venue_model->add_booking('venue_players', $data);
							array_push($co_players, $existing_user);
							$data3 = array(
								'user_id' => $user_id,
								'co_player' => $existing_user,
								'sports_id' => $sports_id,
								'rating' => 0
							);
							$this->venue_model->insert_coplayer($data3);
						} else {
							$data = array(
								'phone_no' => $contact_number,
								'name' => $contact_name,
								'status' => '1',
							);
							$this->venue_model->add_booking('users', $data);

							$user_check = $this->venue_model->get_usercheck($contact_number);
							foreach ($user_check as $key => $value2) {
								$new_user = $value2->id;
							}

							$data = array(
								'user_id' => $new_user,
								'sports_id' => $sports_id,
							);
							$this->venue_model->add_booking('user_sports', $data);
							$data = array(
								'booking_id' => $booking_id,
								'court_id' => $court_id,
								'date' => $date,
								'user_id' => $new_user

							);
							$this->venue_model->add_booking('venue_players', $data);
							array_push($co_players, $new_user);
							$data3 = array(
								'user_id' => $user_id,
								'co_player' => $new_user,
								'sports_id' => $sports_id,
								'rating' => 0
							);
							$this->venue_model->insert_coplayer($data3);
						}

					}

				}
				if (!empty($co_players)) {

					$output = $this->sampling($co_players, 2, $booking_id);
				}

				$result = array(
					'ErrorCode' => 0,
					'Data' => "$booking_id",
					'Message' => "Venue booked"
				);

			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => "Failed",
					'Message' => ""
				);

			}

		} else {
			foreach ($court_time as $key => $value) {
				$this->venue_model->delete_court_book($court_id, $value, $date);
			}
			$result = array(
				'ErrorCode' => 1,
				'Data' => "",
				'Message' => "Already Booked"
			);
		}
		return $this->response($result, 200);
	}

	public function upcoming_booking_get($user_id)
	{
		//print_r($user_id);exit;
		$data1 = [];
		$data = $this->venue_model->get_upcoming_venue_booking($user_id, date("Y-m-d"));
		//print_r($data);exit;

		if (!empty($data)) {
			foreach ($data as $key => $value) {

				$time = explode(" ", $value->time);
				$value->time = $time[0] . " " . date('h:i a ', strtotime($time[1]));

				$court_timing = $this->venue_model->get_venue_booking_court_time($value->booking_id);

				foreach ($court_timing as $key2 => $value2) {
					$value->court_timing[] = date('h:i A', strtotime($value2->court_time));
					$value->capacity[] = $value2->capacity;

				}
				$value->co_players = $this->venue_model->get_venue_players($value->booking_id, $value->user_id);
				$value->duration = count($value->court_timing) * $value->intervel;

			}
			if (!empty($data)) {
				$data[0]->date = explode(" ", $data[0]->date);
				unset($data[0]->date[1]);
				$data[0]->date = $data[0]->date[0];

				foreach ($data as $key => $value) {
					if (date('Y:m:d', strtotime($value->date)) == date('Y:m:d')) {
						if (date('H:i:s', strtotime(max($value->court_timing))) < date('H:i:s')) {

							unset($data[$key]);
						}

					}
				}
				foreach ($data as $key => $value) {
					$data1[] = (array) $value;
				}

				usort($data1, function ($a, $b) {
					if ($a['date'] == $b['date']) {
						return 0;
					}
					return ($a['date'] < $b['date']) ? -1 : 1;
				});

				$result = array(
					'ErrorCode' => 0,
					'Data' => $data1,
					'Message' => ""
				);

			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => "No Data",
					'Message' => ""
				);

			}
			return $this->response($result, 200);
		} else {
			$result = array(
				'ErrorCode' => 1,
				'Data' => "",
				'Message' => "No Data"
			);
			return $this->response($result, 200);
		}
	}

	public function past_booking_get($user_id)
	{

		$data1 = [];
		$data = $this->venue_model->get_past_venue_booking($user_id, date("Y-m-d"));

		if (!empty($data)) {
			foreach ($data as $key => $value) {

				$time = explode(" ", $value->time);
				$value->time = $time[0] . " " . date('h:i a ', strtotime($time[1]));
				$court_timing = $this->venue_model->get_venue_booking_court_time($value->booking_id);

				foreach ($court_timing as $key2 => $value2) {
					$value->court_timing[] = date('h:i A', strtotime($value2->court_time));
					$value->capacity[] = $value2->capacity;

				}
				$value->court_time = '';
				$value->co_players = $this->venue_model->get_venue_players($value->booking_id, $value->user_id);
				$value->duration = count($value->court_timing) * $value->intervel;

			}

			if (!empty($data)) {
				$data[0]->date = explode(" ", $data[0]->date);
				unset($data[0]->date[1]);
				$data[0]->date = $data[0]->date[0];
				//print_r($data);
				foreach ($data as $key => $value) {
					$rating = $this->venue_model->get_venue_rating($value->venue_id, $user_id);
					//print_r($rating);exit;
					$rating_status = $this->venue_model->get_venue_rating2($value->booking_id, $user_id);
					//print_r($rating_status);
					if ($rating) {
						$rating = explode(',', $rating->rating);
						$value->rating = array_sum($rating);
					} else {
						$value->rating = 0;
					}

					if (!empty($rating_status)) {
						$status = $this->venue_model->get_rating($value->booking_id, $user_id);
						if (!empty($status)) {
							$value->rating_status = false;

						} else {
							$value->rating_status = true;
						}
					} else {
						$result = array(
							'ErrorCode' => 1,
							'Data' => '',
							'Message' => "Sorry"
						);
						return $this->response($result, 200);
					}
				}
				//print_r($data);exit;
				foreach ($data as $key => $value) {

					if (date('Y:m:d', strtotime($value->date)) == date('Y:m:d')) {

						if (date('H:i:s', strtotime(max($value->court_timing))) > date('H:i:s')) {

							unset($data[$key]);
						}

					}
				}
				foreach ($data as $key => $value) {
					$data1[] = (array) $value;
				}
				usort($data1, function ($a, $b) {
					if ($a['date'] == $b['date']) {
						return 0;
					}
					return ($a['date'] < $b['date']) ? -1 : 1;
				});

				$result = array(
					'ErrorCode' => 0,
					'Data' => $data1,
					'Message' => ""
				);

			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => '',
					'Message' => "No Data"
				);

			}

			return $this->response($result, 200);
		} else {
			$result = array(
				'ErrorCode' => 1,
				'Data' => '',
				'Message' => "No Data"
			);
			return $this->response($result, 200);
		}
	}

	public function venue_details_get($id)
	{
		$data = $this->venue_model->get_venue_details($id);
		//print_r($data);

		foreach ($data as $key => $val) {
			$val->court = explode(',', $val->court);
			$val->sports = explode(',', $val->sports);
			$val->sports_image = explode(',', $val->sports_image);
			$val->facility = explode(',', $val->facility);
			$val->court_id = explode(',', $val->court_id);
			$val->sports_id = explode(',', $val->sports_id);
			$val->court_sports = explode(',', $val->court_sports);

		}
		foreach ($data as $key => $value) {
			foreach ($value->court as $key2 => $value2) {
				$court_sports[] = array('sports' => $value->court_sports[$key2]);
				$court[] = array(
					'id' => $value->court_id[$key2],
					'court' => $value->court[$key2],
					'sports' => $court_sports

				);
			}
			foreach ($value->sports as $key2 => $value2) {
				$sports[] = array(
					'id' => $value->sports_id[$key2],
					'sports' => $value->sports[$key2],
					'image' => $value->sports_image[$key2]
				);
			}

			$value->sports = $sports;
			$value->court = $court;
			unset($value->court_time, $value->sports_id, $value->court_id, $court, $sports, $value->sports_image, $value->court_sports);

		}

		if (!empty($data)) {
			$result = array(
				'ErrorCode' => 0,
				'Data' => $data,
				'Message' => ""
			);
			return $this->response($result, 200);
		} else {
			$result = array(
				'ErrorCode' => 0,
				'Data' => $data,
				'Message' => "No Data Found"
			);
			return $this->response($result, 200);
		}

	}
	public function booking_payment_post()
	{


		$booking_id = $this->input->post('booking_id');
		$transaction_id = $this->input->post('transaction_id');
		$payment_id = $this->input->post('payment_id');
		$payment_mode = $this->input->post('payment_mode');
		$coupon_id = $this->input->post('coupon_id');
		$court_id = $this->input->post('court_id');
		$court_timing = json_decode($this->input->post('court_time'));
		$date = $this->input->post('date');
		$share_location = $this->input->post('share_location');


		foreach ($court_timing as $key => $value) {
			$this->venue_model->delete_court_book($court_id, $value, $date);
		}

		$players_details = [];
		$players_name = [];

		$booking_details = $this->venue_model->get_venue_booking($booking_id);
		//print_r($booking_details);exit;
		$hosted_user_details = $this->venue_model->users_list($booking_details->user_id);

		$players = $this->venue_model->get_venue_players($booking_id, $booking_details->user_id);

		foreach ($players as $key => $value) {
			$players_details[] = $this->venue_model->users_list($value['user_id']);
		}
		//print_r($players_details);exit;
		if ($payment_mode != '2') {
			if ($coupon_id != '') {
				$coupon_array = array(
					'user_id' => $booking_details->user_id,
					'coupon_id' => $coupon_id,
					'booking_id' => $booking_id
				);
				$used_coupon = $this->venue_model->used_coupon_insert($coupon_array);
			}
		}
		$venue_managers = $this->venue_model->venue_managers($booking_details->venue_id);

		$upupup_mail = $this->venue_model->up_users_mail();

		$upupup_phone = $this->venue_model->up_users_phone();


		foreach ($court_timing as $key => $value) {

			$court_time[] = date('h:i a', strtotime($value));
		}
		foreach ($players_details as $key => $value) {

			$players_name[] = $value['name'];
		}
		foreach ($court_time as $key => $value) {

			$time = "$value";
			$time2 = "$booking_details->intervel minutes";
			$timestamp = strtotime($time . " +" . $time2);


			$Tcourt_time[] = $value . " - " . date("h:i a", $timestamp);
		}

		$Tcourt_time = implode(', ', $Tcourt_time);
		$court_time = implode(', ', $court_time);

		$players_name = implode(', ', $players_name);

		$data = array(
			'transaction_id' => $transaction_id,
			'payment_id' => $payment_id,
			'payment_mode' => $payment_mode,
			'coupon_id' => $coupon_id,
			'status' => 1
		);


		$update_booking = $this->venue_model->update_booking($data, $booking_id);
		if ($share_location == 1) {

			$venue_location = $this->venue_model->get_venue_location($booking_id);

			foreach ($venue_location as $key => $value1) {
				$lat = $value1->lat;
				$long = $value1->lon;

			}
		}



		///sms////////////////////////////////
		if (!empty($booking_details)) {
			if (!empty($update_booking)) {
				if ($payment_mode != 2) {
					//echo "string";exit;
					if ($share_location == 1) {
						if ($players_name) {
							$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name .[Booking ID:$booking_id ] \r\n http://maps.google.com/maps?q=$lat,$long";
						} else {
							$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time .[Booking ID:$booking_id ] \r\n http://maps.google.com/maps?q=$lat,$long";
						}
					} else {

						if ($players_name) {
							$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name .[Booking ID:$booking_id ]";
						} else {
							$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time .[Booking ID:$booking_id ]";
						}

					}


					$this->common->sms(str_replace(' ', '', $hosted_user_details['phone_no']), urlencode($message));

					$title = "New Venue Booked";
					$data_push = array(
						'result' => array(
							'message' => $message,
							'title' => $title,
							'type' => 6
						),
						'status' => "true",
						'type' => "GENERAL",
						'venue_id' => $booking_details->venue_id
					);

					$notification = $this->notification->push_notification(array($hosted_user_details), $message, $title, $data_push);



					$data['data'] = array(
						'user' => $hosted_user_details,
						'booking' => $booking_details,
						'court_timing' => $court_time,
						'co_players' => $players_name,
						'mode' => $payment_mode,
						'coupon' => $this->venue_model->get_coupon($coupon_id),
						'end_time' => $Tcourt_time

					);



					$to_email = $hosted_user_details['email'];
					$subject = 'Venue Booked';
					$this->load->library('email');
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = 'upupup.in';
					$config['smtp_port'] = '25';
					$config['smtp_timeout'] = '7';
					$config['smtp_user'] = 'admin@upupup.in';
					$config['smtp_pass'] = '%S+1q)yiC@DW';
					$config['charset'] = 'utf-8';
					$config['newline'] = '\r\n';
					$config['mailtype'] = 'html'; // or html
					$config['validation'] = TRUE; // bool whether to validate email or not
					$this->email->initialize($config);
					$this->load->library('email', $config);
					$this->email->set_newline("\r\n");
					$this->email->from('booking@upupup.in', 'upUPUP.');
					$this->email->to($to_email);
					$this->email->subject($subject);

					$message = $this->load->view('booking_mail', $data, true);
					$this->email->message($message);
					$this->email->send();
					//print_r($hosted_user_details);exit;

					if (!empty($players_details)) {

						foreach ($players_details as $key => $value) {

							if ($share_location == 1) {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer .[Booking ID:$booking_id ]\r\n http://maps.google.com/maps?q=$lat,$long");
							} else {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer .[Booking ID:$booking_id ] \r\n http://maps.google.com/maps?q=$lat,$long");
							}

							$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));

							$title = "New Venue Booked";
							$data_push = array(
								'result' => array(
									'message' => $message,
									'title' => $title,
									'type' => 6
								),
								'status' => "true",
								'type' => "GENERAL",
								'venue_id' => $booking_details->venue_id
							);
							$device_id[0] = array('device_id' => $value['device_id']);
							$notification = $this->notification->push_notification($device_id, $message, $title, $data_push);

							$to_email = $value['email'];
							$subject = 'Venue Booked';
							$this->load->library('email');
							$config['protocol'] = 'smtp';
							$config['smtp_host'] = 'upupup.in';
							$config['smtp_port'] = '25';
							$config['smtp_timeout'] = '7';
							$config['smtp_user'] = 'admin@upupup.in';
							$config['smtp_pass'] = '%S+1q)yiC@DW';
							$config['charset'] = 'utf-8';
							$config['newline'] = '\r\n';
							$config['mailtype'] = 'html'; // or html
							$config['validation'] = TRUE; // bool whether to validate email or not
							$this->email->initialize($config);
							$this->load->library('email', $config);
							$this->email->set_newline("\r\n");
							$this->email->from('booking@upupup.in', 'upUPUP.');
							$this->email->to($to_email);
							$this->email->subject($subject);
							$data['data']['Coplayer'] = '1';
							$message = $this->load->view('booking_mail2', $data, true);
							$this->email->message($message);
							$this->email->send();

						}
					}
					$data['data']['Coplayer'] = '';

					if (!empty($venue_managers)) {
						foreach ($venue_managers as $key => $value) {
							if ($players_name) {
								$message = ("$booking_details->name has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name .[Booking ID:$booking_id ]");

							} else {
								$message = ("$booking_details->name has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time .[Booking ID:$booking_id ]");
							}
							$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));

							$subject = 'Venue Booked';
							$to_email = $value['email'];

							$this->load->library('email');
							$config['protocol'] = 'smtp';
							$config['smtp_host'] = 'upupup.in';
							$config['smtp_port'] = '25';
							$config['smtp_timeout'] = '7';
							$config['smtp_user'] = 'admin@upupup.in';
							$config['smtp_pass'] = '%S+1q)yiC@DW';
							$config['charset'] = 'utf-8';
							$config['newline'] = '\r\n';
							$config['mailtype'] = 'html'; // or html
							$config['validation'] = TRUE; // bool whether to validate email or not
							$this->email->initialize($config);
							$this->load->library('email', $config);
							$this->email->set_newline("\r\n");
							$this->email->from('booking@upupup.in', 'upUPUP.');
							$this->email->to($to_email);
							$this->email->subject($subject);
							$message = $this->load->view('booking_mail2', $data, true);
							$this->email->message($message);
							$this->email->send();
						}
					}
					//exit;
					foreach ($upupup_phone as $key => $value) {
						if ($players_name) {
							$message = ("$booking_details->name has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name .[Booking ID:$booking_id ]");
						} else {
							$message = ("$booking_details->name has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time  .[Booking ID:$booking_id ]");
						}
						$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));

					}
					foreach ($upupup_mail as $key => $value) {


						$subject = 'Venue Booked';
						$to_email = $value->email;

						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = 'upupup.in';
						$config['smtp_port'] = '25';
						$config['smtp_timeout'] = '7';
						$config['smtp_user'] = 'admin@upupup.in';
						$config['smtp_pass'] = '%S+1q)yiC@DW';
						$config['charset'] = 'utf-8';
						$config['newline'] = '\r\n';
						$config['mailtype'] = 'html'; // or html
						$config['validation'] = TRUE; // bool whether to validate email or not
						$this->email->initialize($config);
						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from('booking@upupup.in', 'upUPUP.');
						$this->email->to($to_email);
						$this->email->subject($subject);
						$message = $this->load->view('booking_mail2', $data, true);
						$this->email->message($message);
						$this->email->send();
					}


				}

			}
		}
		if (!empty($update_booking)) {
			$result = array(
				'ErrorCode' => 0,
				'Data' => '',
				'Message' => "Booking Completed"
			);
		} else {
			$result = array(
				'ErrorCode' => 1,
				'Data' => '',
				'Message' => "Booking Failed"
			);
		}
		return $this->response($result, 200);

	}

	public function test_get($value = '')
	{
		date_default_timezone_set("Asia/Bangkok");
		$data = $this->venue_model->send_match_sms();

		foreach ($data as $key => $value) {


			//print_r($users);exit;
			if (date('H:i:s', strtotime('-1 hour', strtotime($value->court_time))) == date('H:i:s')) {
				$users[] = $this->venue_model->get_users($value->booking_id);
				foreach ($users as $key2 => $value2) {
					foreach ($value2 as $key3 => $value3) {
						$message = "Match%20is%20starting";
						$this->common->sms($value3->phone_no, $message);
					}
				}
				/*$message="Venue%20Booked!<br>Booking%20ID:$booking_id";
						 $this->common->sms($value['phone_no'],$message);*/
			}

		}

	}
	public function court_book_post()
	{
		date_default_timezone_set('Asia/Kolkata');
		$user_id = $this->input->post('user_id');
		$court_time = $this->input->post('court_time');
		$court_id = $this->input->post('court_id');
		$date = $this->input->post('date');
		$capacity = $this->input->post('capacity');
		$y_n = $this->input->post('status');
		$court_capacity = $this->venue_model->get_court($court_id)[0]->capacity;
		$status = $this->venue_model->get_court_book($court_time, $court_id, $date, $court_capacity);
		$bookcap = $this->venue_model->check_court_bookedcp($court_id, $court_time, $date);
		$tempcap = $this->venue_model->check_court_tempcp($court_time, $court_id, $date);
		//print_r($tempcap);exit;
		$check = 0;
		//////// total booked capacity sum from venue_booking_time table start ////////////
		if (!empty($bookcap)) {
			foreach ($bookcap as $row) {
				$bookcap_sum = $row->sum;
			}
		} else {
			$bookcap_sum = 0;
		}
		//////// total booked capacity sum from venue_booking_time table end ////////////
		//////// total booked capacity sum from court_book table start ////////////
		if (!empty($tempcap)) {
			foreach ($tempcap as $row) {
				$tempcap_sum = $row->tempsum;
			}
		} else {
			$tempcap_sum = 0;
		}
		//////// total booked capacity sum from court_book table end ////////////

		$booksum_cap = $bookcap_sum + $tempcap_sum;

		$available_capa = $court_capacity - $booksum_cap;

		if ($bookcap_sum != 0 || $tempcap_sum != 0) {
			if ($available_capa < $capacity) {
				if (empty($status)) {
					$check = 1;
				}
			}
		}


		if ($y_n == 'yes') {
			if ($check == 0) {
				if (empty($status)) {
					$data = array(
						'court_id' => $court_id,
						'court_time' => $court_time,
						'date' => $date,
						'capacity' => $capacity,
						'user_id' => $user_id,
						'time_stamp' => date('H:i:s')
					);
					$this->venue_model->add_court_book($data);
					$result = array(
						'ErrorCode' => 0,
						'Data' => '',
						'Message' => "Success"
					);
				} else {
					$result = array(
						'ErrorCode' => 1,
						'Data' => '',
						'Message' => "Already Booked"
					);
				}

			} else {
				$result = array(
					'ErrorCode' => 2,
					'Data' => $available_capa,
					'Message' => "Selected capacity not available"
				);

			}

		} else {
			$this->venue_model->delete_court_book($court_id, $court_time, $date, $user_id);
			$result = array(
				'ErrorCode' => 1,
				'Data' => '',
				'Message' => "Deleted"
			);
		}
		return $this->response($result, 200);
	}



	public function booking_test()
	{

		$user_id = $this->input->post('user_id');
		$sports_id = $this->input->post('sports_id');
		$date = $this->input->post('date');
		$court_id = $this->input->post('court_id');
		$venue_id = $this->input->post('venue_id');
		$co_players = json_decode($this->input->post('co_players'));
		$co_players_contact = json_decode($this->input->post('co_players_contact'));
		$court_time = json_decode($this->input->post('court_time'));
		$cap = json_decode($this->input->post('capacity'));
		$coupon_id = $this->input->post('coupon_id');
		$offer = $this->input->post('offer');
		$price = $this->input->post('price');
		$cost = $this->input->post('cost');
		$balance = $this->input->post('balance');
		$mode = $this->input->post('mode');
		$offer_id = json_decode($this->input->post('offer_id'));
		$check = 0;


		$capacity = $this->venue_model->get_court($court_id)[0]->capacity;

		foreach ($court_time as $key => $value) {

			$status = $this->venue_model->check_court_booked($court_id, array('time' => $value), $date, $capacity);
			//print_r($bookcap_sum);exit;
			if (!empty($status)) {
				foreach ($status as $row) {
					$bookcap_sum = $row->sum;
				}

				$available_capa = $capacity - $bookcap_sum;
				//print_r($bookcap_sum);exit;
				if ($available_capa < $cap[$key]) {
					$check = 1;
				}
			}


			//  if(!empty($status))
			// 	$check=1;
		}
		//

		if ($check == 0) {

			$booking_id = new DateTime();
			$booking_id = $user_id + $booking_id->getTimestamp();

			$data = array(
				'user_id' => $user_id,
				'sports_id' => $sports_id,
				'date' => $date,
				'court_id' => $court_id,
				'venue_id' => $venue_id,
				'cost' => $cost,
				'booking_id' => $booking_id,
				'payment_mode' => '2',
				'coupon_id' => '0',
				'offer_value' => $offer,
				'price' => $price,
				'bal' => $balance,
				'time' => date('Y-m-d h:i:sa')
			);

			$add = $this->venue_model->add_booking('venue_booking', $data);

			foreach ($offer_id as $key => $value) {
				$data = array(
					'booking_id' => $booking_id,
					'offer_id' => $value,
				);
				$this->venue_model->add_booking('booking_offer', $data);
			}

			foreach ($court_time as $key => $value) {
				$data = array(
					'booking_id' => $booking_id,
					'court_time' => date("H:i:s", strtotime($value)),
					'court_id' => $court_id,
					'capacity' => $cap[$key],
					'date' => $date
				);
				$this->venue_model->add_booking('venue_booking_time', $data);
			}
			if ($add) {
				if (!empty($co_players)) {

					foreach ($co_players as $key => $value) {
						$data = array(
							'booking_id' => $booking_id,
							'court_id' => $court_id,
							'date' => $date,
							'user_id' => $value

						);
						$this->venue_model->add_booking('venue_players', $data);

					}
					$output = $this->sampling($co_players, 2, $booking_id);
				}


				$result = array(
					'ErrorCode' => 0,
					'Data' => "$booking_id",
					'Message' => "Venue booked"
				);

			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => "Failed",
					'Message' => ""
				);

			}

		} else {
			foreach ($court_time as $key => $value) {
				$this->venue_model->delete_court_book($court_id, $value, $date);
			}
			$result = array(
				'ErrorCode' => 1,
				'Data' => "",
				'Message' => "Already Booked"
			);
		}
		return $this->response($result, 200);
	}

	public function sampling($chars, $size, $booking_id, $combinations = array())
	{


		if (empty($combinations)) {
			$combinations = $chars;
		}

		# we're done if we're at size 1
		if ($size == 1) {
			return $combinations;
		}

		# initialise array to put new values in
		$new_combinations = array();

		# loop through existing combinations and character set to create strings
		foreach ($combinations as $combination) {
			foreach ($chars as $char) {
				//$new_combinations[] = $combination . $char;
				$data = array(
					'booking_id' => $booking_id,
					'coplayer' => $combination,
					'coplayer2' => $char,
					'status' => '0'

				);
				$this->venue_model->add_booking('booking_rating', $data);


			}
		}


		return $this->sampling($chars, $size - 1, $new_combinations);

	}

	/////////////////////////////////////////////////////////////////////////////////
	public function booking_testoff_get()
	{

		$lat = "9.98185";
		$long = "76.305";

		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyD_UkF5H840Ww7fN581ySV_l0gqIU4cwZ4";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, "");
		$curlData = curl_exec($curl);
		curl_close($curl);



	}
	///////////////////////////////////////////////////////////////////////////////////	


	//////////////////////////***************************************************************************************************************************//////////////////////

	public function booking_demo_test_post()
	{

		$user_id = $this->input->post('user_id');
		$sports_id = $this->input->post('sports_id');
		$date = $this->input->post('date');
		$court_id = $this->input->post('court_id');
		$venue_id = $this->input->post('venue_id');
		$co_players = json_decode($this->input->post('co_players'));
		$co_players_contact = json_decode($this->input->post('co_players_contact'));
		$court_time = json_decode($this->input->post('court_time'));
		$cap = json_decode($this->input->post('capacity'));
		$coupon_id = $this->input->post('coupon_id');
		$offer = $this->input->post('offer');
		$price = $this->input->post('price');
		$cost = $this->input->post('cost');
		$balance = $this->input->post('balance');
		$mode = $this->input->post('mode');
		$offer_id = json_decode($this->input->post('offer_id'));
		$payment_mode = $this->input->post('payment_mode');
		$upcoin_setting_id = $this->input->post('upcoin_setting_id');
		$rupee = $this->input->post('rupee');
		$coin = $this->input->post('coin');
		$share_location = $this->input->post('share_location');
		$service_id = $this->input->post('service_id');
		$service_amount = $this->input->post('service_amount');
		$service_total = $this->input->post('service_total');
		$check = 0;
		$user_availability = $this->venue_model->get_user_availability($user_id);
		if (empty($user_availability)) {
			$result = array(
				'ErrorCode' => 1,
				'Data' => "Failed",
				'Message' => "User not exist"
			);
			return $this->response($result, 200);
		}

		$capacity = $this->venue_model->get_court($court_id)[0]->capacity;

		foreach ($court_time as $key => $value) {

			$status = $this->venue_model->check_court_booked($court_id, array('time' => $value), $date, $capacity);
			//print_r($bookcap_sum);exit;
			if (!empty($status)) {
				foreach ($status as $row) {
					$bookcap_sum = $row->sum;
				}

				$available_capa = $capacity - $bookcap_sum;
				//print_r($bookcap_sum);exit;
				if ($available_capa < $cap[$key]) {
					$check = 1;
				}
			}


		}
		//

		if ($check == 0) {

			if ($payment_mode == 1) {

				/* ------------------------------------------------------ booking using payment gateway start  ------------------------------------------------------- */
				$booking_id = new DateTime();
				$booking_id = $user_id + $booking_id->getTimestamp();

				$data = array(
					'user_id' => $user_id,
					'sports_id' => $sports_id,
					'date' => $date,
					'court_id' => $court_id,
					'venue_id' => $venue_id,
					'cost' => $cost,
					'booking_id' => $booking_id,
					'payment_mode' => '2',
					'coupon_id' => '0',
					'offer_value' => $offer,
					'price' => $price,
					'bal' => $balance,
					'time' => date('Y-m-d H:i:s')
				);

				$add = $this->venue_model->add_booking('venue_booking', $data);

				foreach ($offer_id as $key => $value1) {
					$offer_id = $value1->offer_id;
					$offer_type = $value1->offer_type;

					if ($offer_type == 1) {
						$data = array(
							'booking_id' => $booking_id,
							'offer_id' => $offer_id,
						);
						$this->venue_model->add_booking('booking_offer', $data);
					}

					if ($offer_type == 2) {
						$data = array(
							'booking_id' => $booking_id,
							'hot_offer_id' => $offer_id,
						);
						$this->venue_model->add_booking('booking_hot_offer', $data);
					}
				}

				foreach ($court_time as $key => $value) {
					$data = array(
						'booking_id' => $booking_id,
						'court_time' => date("H:i:s", strtotime($value)),
						'court_id' => $court_id,
						'capacity' => $cap[$key],
						'date' => $date
					);
					$this->venue_model->add_booking('venue_booking_time', $data);
				}
				if ($add) {
					if (!empty($co_players)) {

						foreach ($co_players as $key => $value) {
							$data = array(
								'booking_id' => $booking_id,
								'court_id' => $court_id,
								'date' => $date,
								'user_id' => $value

							);
							$this->venue_model->add_booking('venue_players', $data);

							$co_player_id = $value;
							$coplayer_sports = $this->venue_model->get_coplayer_sports($sports_id, $co_player_id);
							if (!empty($coplayer_sports)) {

								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $value,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);

							}



						}

					}

					if (!empty($co_players_contact)) {

						foreach ($co_players_contact as $key => $value1) {
							$contact_name = $value1->contact_name;
							$contact_number = $value1->contact_number;

							$user_check = $this->venue_model->get_usercheck($contact_number);

							if (!empty($user_check)) {

								foreach ($user_check as $key => $value2) {
									$existing_user = $value2->id;
								}

								$data = array(
									'booking_id' => $booking_id,
									'court_id' => $court_id,
									'date' => $date,
									'user_id' => $existing_user

								);
								$this->venue_model->add_booking('venue_players', $data);
								array_push($co_players, $existing_user);
								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $existing_user,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);
							} else {
								$data = array(
									'phone_no' => $contact_number,
									'name' => $contact_name,
									'status' => '1',
								);
								$this->venue_model->add_booking('users', $data);

								$user_check = $this->venue_model->get_usercheck($contact_number);
								foreach ($user_check as $key => $value2) {
									$new_user = $value2->id;
								}

								$data = array(
									'user_id' => $new_user,
									'sports_id' => $sports_id,
								);
								$this->venue_model->add_booking('user_sports', $data);
								$data = array(
									'booking_id' => $booking_id,
									'court_id' => $court_id,
									'date' => $date,
									'user_id' => $new_user

								);
								$this->venue_model->add_booking('venue_players', $data);
								array_push($co_players, $new_user);
								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $new_user,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);
							}

						}

					}
					if (!empty($co_players)) {

						$output = $this->sampling($co_players, 2, $booking_id);
					}

					$result = array(
						'ErrorCode' => 0,
						'Data' => "$booking_id",
						'Message' => "Venue booked"
					);

				} else {
					$result = array(
						'ErrorCode' => 1,
						'Data' => "Failed",
						'Message' => ""
					);

				}

				/* ------------------------------------------------------ booking using payment gateway end   -------------------------------------------------------- */

			} elseif ($payment_mode == 2) {

				/* ------------------------------------------------------ booking using up coin start  -------------------------------------------------------------- */

				$booking_id = new DateTime();
				$booking_id = $user_id + $booking_id->getTimestamp();

				$data = array(
					'user_id' => $user_id,
					'sports_id' => $sports_id,
					'date' => $date,
					'court_id' => $court_id,
					'venue_id' => $venue_id,
					'cost' => $cost,
					'booking_id' => $booking_id,
					'payment_mode' => '1',
					'payment_id' => "up_coin",
					'coupon_id' => $coupon_id,
					'offer_value' => $offer,
					'price' => $price,
					'bal' => $balance,
					'time' => date('Y-m-d H:i:s')
				);

				$add = $this->venue_model->add_booking('venue_booking', $data);
				foreach ($offer_id as $key => $value1) {
					$offer_id = $value1->offer_id;
					$offer_type = $value1->offer_type;

					if ($offer_type == 1) {
						$data = array(
							'booking_id' => $booking_id,
							'offer_id' => $offer_id,
						);
						$this->venue_model->add_booking('booking_offer', $data);
					}

					if ($offer_type == 2) {
						$data = array(
							'booking_id' => $booking_id,
							'hot_offer_id' => $offer_id,
						);
						$this->venue_model->add_booking('booking_hot_offer', $data);
					}
				}

				if ($coupon_id != '') {
					$coupon_array = array(
						'user_id' => $user_id,
						'coupon_id' => $coupon_id,
						'booking_id' => $booking_id
					);
					$used_coupon = $this->venue_model->used_coupon_insert($coupon_array);
				}
				$service_data = array(
					'booking_id' => $booking_id,
					'service_charge_id' => $service_id,
					'amount ' => $service_amount,
					'total_service_charge' => (int) $service_total,
					'added_date' => date('Y-m-d H:i:s'),
				);
				$this->venue_model->add_booking('service_charge_booking', $service_data);

				foreach ($court_time as $key => $value) {
					$data = array(
						'booking_id' => $booking_id,
						'court_time' => date("H:i:s", strtotime($value)),
						'court_id' => $court_id,
						'capacity' => $cap[$key],
						'date' => $date
					);
					$this->venue_model->add_booking('venue_booking_time', $data);
				}
				if ($add) {
					if (!empty($co_players)) {

						foreach ($co_players as $key => $value) {
							$data = array(
								'booking_id' => $booking_id,
								'court_id' => $court_id,
								'date' => $date,
								'user_id' => $value

							);
							$this->venue_model->add_booking('venue_players', $data);

							$co_player_id = $value;
							$coplayer_sports = $this->venue_model->get_coplayer_sports($sports_id, $co_player_id);
							if (!empty($coplayer_sports)) {

								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $value,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);

							}



						}

					}

					if (!empty($co_players_contact)) {

						foreach ($co_players_contact as $key => $value1) {
							$contact_name = $value1->contact_name;
							$contact_number = $value1->contact_number;

							$user_check = $this->venue_model->get_usercheck($contact_number);

							if (!empty($user_check)) {

								foreach ($user_check as $key => $value2) {
									$existing_user = $value2->id;
								}

								$data = array(
									'booking_id' => $booking_id,
									'court_id' => $court_id,
									'date' => $date,
									'user_id' => $existing_user

								);
								$this->venue_model->add_booking('venue_players', $data);
								array_push($co_players, $existing_user);
								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $existing_user,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);
							} else {
								$data = array(
									'phone_no' => $contact_number,
									'name' => $contact_name,
									'status' => '1',
								);
								$this->venue_model->add_booking('users', $data);

								$user_check = $this->venue_model->get_usercheck($contact_number);
								foreach ($user_check as $key => $value2) {
									$new_user = $value2->id;
								}

								$data = array(
									'user_id' => $new_user,
									'sports_id' => $sports_id,
								);
								$this->venue_model->add_booking('user_sports', $data);
								$data = array(
									'booking_id' => $booking_id,
									'court_id' => $court_id,
									'date' => $date,
									'user_id' => $new_user

								);
								$this->venue_model->add_booking('venue_players', $data);
								array_push($co_players, $new_user);
								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $new_user,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);
							}

						}

					}
					if (!empty($co_players)) {

						$output = $this->sampling($co_players, 2, $booking_id);
					}

					$my_accounts = $this->venue_model->get_my_account($user_id);
					if (!empty($my_accounts)) {

						foreach ($my_accounts as $row) {
							$up_coin = $row->up_coin;
							$bonus_coin = $row->bonus_coin;
							$total = $row->total;
						}

						if ($bonus_coin == $coin) {
							$bonus_coin = $bonus_coin - $coin;
							$total = $total - $coin;
							$update_data = array(
								'bonus_coin' => $bonus_coin,
								'total' => $total
							);
							$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
						} elseif ($bonus_coin > $coin) {
							$bonus_coin = $bonus_coin - $coin;
							$total = $total - $coin;
							$update_data = array(
								'bonus_coin' => $bonus_coin,
								'total' => $total
							);
							$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
						} elseif ($bonus_coin < $coin && $bonus_coin != 0) {
							$remaining_coin = $coin - $bonus_coin;
							$avail_bonus_coin = $coin - $remaining_coin;
							$bonus_coin = $bonus_coin - $avail_bonus_coin;
							$up_coin = $up_coin - $remaining_coin;
							$total = $total - $coin;
							$update_data = array(
								'bonus_coin' => $bonus_coin,
								'up_coin' => $up_coin,
								'total' => $total
							);
							$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
						} elseif ($bonus_coin < $coin && $bonus_coin == 0) {
							$up_coin = $up_coin - $coin;
							$total = $total - $coin;
							$update_data = array(
								'up_coin' => $up_coin,
								'total' => $total
							);
							$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
						}
					}



					/* ---------------------------------------------------------- booking bonus start ---------------------------------------------------------------------------------*/

					$data = array(
						'booking_id' => $booking_id,
						'payment_mode_id' => $payment_mode,
						'upcoin_setting_id' => $upcoin_setting_id,
						'rupee' => $rupee,
						'coin' => $coin,
						'date' => date('Y-m-d'),
						'added_date' => date('Y-m-d H:i:s'),
					);

					$add = $this->venue_model->add_booking('booking_payment_mode', $data);

					$book_bonus_applied = $this->venue_model->get_book_bonus_applied($user_id);
					if (empty($book_bonus_applied)) {
						/*############################### if initial booking bonus not applied start ############################*/
						$first_booking = $this->venue_model->get_first_booking($user_id);
						foreach ($first_booking as $row) {
							$booking_count = $row->booking_count;
						}
						if ($booking_count == 1) {
							$user_location = $this->venue_model->get_user_locationbok($user_id);
							foreach ($user_location as $row) {
								$location_id = $row->location_id;
							}
							$check_book_setting = $this->venue_model->get_check_book_setting($location_id);
							if (!empty($check_book_setting)) {

								foreach ($check_book_setting as $row) {
									$bkset_id = $row->id;
									$bkset_coin = $row->coin;
								}
								$my_account = $this->venue_model->get_my_account($user_id);
								if (!empty($my_account)) {

									foreach ($my_account as $row) {
										$up_coin = $row->up_coin;
										$bonus_coin = $row->bonus_coin;
										$total = $row->total;
									}
									$bonus_coin = $bonus_coin + $bkset_coin;
									$total = $total + $bkset_coin;
									$update_data = array(
										'bonus_coin' => $bonus_coin,
										'total' => $total
									);
									$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
									$data = array(
										'booking_id' => $booking_id,
										'users_id' => $user_id,
										'booking_bonus_setting_id' => $bkset_id,
										'bonus_coin' => $bkset_coin,
										'date' => date('Y-m-d'),
										'added_date' => date("Y-m-d H:i:s", strtotime("+1 sec")),
									);

									$this->venue_model->add_booking('booking_bonus', $data);

								} else {
									$up_coin = 0;
									$bonus_coin = 0;
									$total = 0;
									$bonus_coin = $bonus_coin + $bkset_coin;
									$total = $total + $bkset_coin;

									$data = array(
										'users_id' => $user_id,
										'up_coin' => $up_coin,
										'bonus_coin' => $bonus_coin,
										'total' => $total,
										'added_date' => date('Y-m-d H:i:s'),
									);

									$this->venue_model->add_booking('my_account', $data);
									$data = array(
										'booking_id' => $booking_id,
										'users_id' => $user_id,
										'booking_bonus_setting_id' => $bkset_id,
										'bonus_coin' => $bkset_coin,
										'date' => date('Y-m-d'),
										'added_date' => date("Y-m-d H:i:s", strtotime("+1 sec")),
									);

									$this->venue_model->add_booking('booking_bonus', $data);
								}
							}

							/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ refered friend booking bonus start $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$*/
							$user_location = $this->venue_model->get_user_locationbok($user_id);
							foreach ($user_location as $row) {
								$location_id = $row->location_id;
							}
							$referal_booking_bonus_set = $this->venue_model->get_referal_booking_bonus_set($location_id);
							if (!empty($referal_booking_bonus_set)) {
								foreach ($referal_booking_bonus_set as $row) {
									$refer_book_set_id = $row->id;
									$refer_booking_bonus_coin = $row->booking_bonus_coin;
								}
								$referal_details = $this->venue_model->get_referal_details($user_id);
								if (!empty($referal_details)) {
									foreach ($referal_details as $row) {
										$refered_id = $row->id;
										$refered_user_id = $row->users_id;
										$refered_referal_id = $row->referral_id;
									}
									$user_referal_checks = $this->venue_model->get_user_referal_checks($refered_user_id, $user_id);
									$booking_bonus_applied = $this->venue_model->get_booking_bonus_applied($refered_user_id, $user_id);
									if (!empty($user_referal_checks) && empty($booking_bonus_applied)) {
										$my_account = $this->venue_model->get_my_account($refered_user_id);

										if (!empty($my_account)) {

											foreach ($my_account as $row) {
												$up_coin = $row->up_coin;
												$bonus_coin = $row->bonus_coin;
												$total = $row->total;
											}
											$bonus_coin = $bonus_coin + $refer_booking_bonus_coin;
											$total = $total + $refer_booking_bonus_coin;
											$update_data = array(
												'bonus_coin' => $bonus_coin,
												'total' => $total
											);
											$this->venue_model->update_my_account($update_data, 'my_account', $refered_user_id);
											$data = array(
												'booking_id' => $booking_id,
												'user_id' => $refered_user_id,
												'booked_user_id' => $user_id,
												'refer_bonus_setting_id' => $refer_book_set_id,
												'bonus_coin' => $refer_booking_bonus_coin,
												'date' => date('Y-m-d'),
												'added_date' => date('Y-m-d H:i:s'),
											);
											$add = $this->venue_model->add_booking('referal_booking_bonus', $data);
										} else {
											$up_coin = 0;
											$bonus_coin = 0;
											$total = 0;
											$bonus_coin = $bonus_coin + $refer_booking_bonus_coin;
											$total = $total + $refer_booking_bonus_coin;
											$data = array(
												'users_id' => $refered_user_id,
												'up_coin' => $up_coin,
												'bonus_coin' => $bonus_coin,
												'total' => $total,
												'added_date' => date('Y-m-d H:i:s'),
											);
											$this->venue_model->add_booking('my_account', $data);
											$data = array(
												'booking_id' => $booking_id,
												'user_id' => $refered_user_id,
												'booked_user_id' => $user_id,
												'refer_bonus_setting_id' => $refer_book_set_id,
												'bonus_coin' => $refer_booking_bonus_coin,
												'date' => date('Y-m-d'),
												'added_date' => date('Y-m-d H:i:s'),
											);
											$add = $this->venue_model->add_booking('referal_booking_bonus', $data);
										}
									}

								}
							}
							/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ refered friend booking bonus end $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$*/

						}
						/* ############################if initial booking bonus not applied end #####################*/

					}



					/* ------------------------------------------------------------------- booking bonus end -----------------------------------------------------------------------*/


					foreach ($court_time as $key => $value) {
						$this->venue_model->delete_court_book($court_id, $value, $date);
					}

					$players_details = [];
					$players_name = [];

					$booking_details = $this->venue_model->get_venue_booking($booking_id);
					//print_r($booking_details);exit;
					$hosted_user_details = $this->venue_model->users_list($booking_details->user_id);

					$players = $this->venue_model->get_venue_players($booking_id, $booking_details->user_id);

					foreach ($players as $key => $value) {
						$players_details[] = $this->venue_model->users_list($value['user_id']);
					}

					$venue_managers = $this->venue_model->venue_managers($booking_details->venue_id);

					$upupup_mail = $this->venue_model->up_users_mail();

					$upupup_phone = $this->venue_model->up_users_phone();


					foreach ($court_timing as $key => $value) {

						$court_time[] = date('h:i a', strtotime($value));
					}
					foreach ($players_details as $key => $value) {

						if ($value['name'] == "upUPUP User") {
							$players_name[] = $value['name'] . " ( " . $value['phone_no'] . " )";
						} else {
							$players_name[] = $value['name'];
						}
					}

					$cp_name = "";
					$cp_phone = "";
					foreach ($players_details as $key => $value) {

						$pla_name = $value['name'];
						$cp_phone = $value['phone_no'];
						$cpd_name = $cp_name . $pla_name . "(" . $cp_phone . ")";
						$cp_name = $cpd_name . ",";
					}


					foreach ($court_time as $key => $value) {

						$time = "$value";
						$time2 = "$booking_details->intervel minutes";
						$timestamp = strtotime($time . " +" . $time2);


						$Tcourt_time[] = $value . " - " . date("h:i A", $timestamp);
					}

					$Tcourt_time = implode(', ', $Tcourt_time);
					$court_time = implode(', ', $court_time);

					$players_name = implode(', ', $players_name);

					if ($share_location == 1) {

						$venue_location = $this->venue_model->get_venue_location($booking_id);

						foreach ($venue_location as $key => $value1) {
							$lat = $value1->lat;
							$long = $value1->lon;

						}
					}
					$payment_mode_details = $this->venue_model->get_payment_mode_details($booking_id);
					foreach ($payment_mode_details as $row) {
						$booking_rupee = $row->rupee;
						$booking_coin = $row->coin;
					}

					$bk_count_text = "";
					$booking_capacity_count = $this->venue_model->get_booking_capacity_count($booking_id);
					foreach ($booking_capacity_count as $row) {
						$booking_count = $row->capacity;

						if ($booking_count > 1) {
							$booking_no_player = "No. of Players : " . $booking_count . " ,";
						} else {
							$booking_no_player = "";
						}
						$bk_count_text = $bk_count_text . $booking_no_player;
					}
					///sms////////////////////////////////
					if (!empty($booking_details)) {
						$booking_service_charge = $this->venue_model->get_booking_service_charge($booking_id);
						if (empty($booking_service_charge)) {
							$bk_service_charge = 0;
						} else {
							foreach ($booking_service_charge as $row) {
								$bk_service_charge = $row->total_service_charge;
							}
						}
						//echo "string";exit;
						if ($share_location == 1) {
							if ($players_name) {
								$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ] \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long";
							} else {
								$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ] \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long";
							}
						} else {

							if ($players_name) {
								$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]";
							} else {
								$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]";
							}

						}


						$this->common->sms(str_replace(' ', '', $hosted_user_details['phone_no']), urlencode($message));

						$title = "New Venue Booked";
						$data_push = array(
							'result' => array(
								'message' => $message,
								'title' => $title,
								'type' => 6
							),
							'status' => "true",
							'type' => "GENERAL",
							'venue_id' => $booking_details->venue_id
						);

						$notification = $this->notification->push_notification(array($hosted_user_details), $message, $title, $data_push);

						$book_off_amount = $price - $cost;
						$gmap_link = "http://maps.google.com/maps?q=$lat,$long";
						$data['data'] = array(
							'user' => $hosted_user_details,
							'booking' => $booking_details,
							'court_timing' => $court_time,
							'co_players' => $players_name,
							'mode' => $payment_mode,
							'coupon' => $this->venue_model->get_coupon($coupon_id),
							'rupee' => $booking_rupee,
							'coin' => $booking_coin,
							'payable_amount' => (int) $cost,
							'total_amount' => (int) $price,
							'offer_value' => $offer,
							'off_amount' => $book_off_amount,
							'no_players' => $bk_count_text,
							'map_link' => $gmap_link,
							'service_charge' => (int) $service_total,
							'share_location' => $share_location,
							'end_time' => $Tcourt_time

						);



						$to_email = $hosted_user_details['email'];
						$subject = 'Venue Booked';
						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = 'upupup.in';
						$config['smtp_port'] = '25';
						$config['smtp_timeout'] = '7';
						$config['smtp_user'] = 'admin@upupup.in';
						$config['smtp_pass'] = '%S+1q)yiC@DW';
						$config['charset'] = 'utf-8';
						$config['newline'] = '\r\n';
						$config['mailtype'] = 'html'; // or html
						$config['validation'] = TRUE; // bool whether to validate email or not
						$this->email->initialize($config);
						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from('booking@upupup.in', 'upUPUP.');
						$this->email->to($to_email);
						$this->email->subject($subject);

						$message = $this->load->view('booking_upcoin_mail', $data, true);
						$this->email->message($message);
						$this->email->send();
						//print_r($hosted_user_details);exit;

						if (!empty($players_details)) {

							foreach ($players_details as $key => $value) {

								if ($share_location == 1) {

									if (!empty($value['device_id'])) {
										$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ]\r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long");
									} else {
										$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ]\r\n For exciting offers, install the upUPUP android app from Google play store https://play.google.com/store/apps/details?id=com.planetpriorities.upupup \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long");
										$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));
									}
								} else {

									if (!empty($value['device_id'])) {
										$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ] ");
									} else {
										$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ] \r\n For exciting offers, install the upUPUP android app from Google play store https://play.google.com/store/apps/details?id=com.planetpriorities.upupup");
										$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));
									}
								}



								$title = "New Venue Booked";
								$data_push = array(
									'result' => array(
										'message' => $message,
										'title' => $title,
										'type' => 6
									),
									'status' => "true",
									'type' => "GENERAL",
									'venue_id' => $booking_details->venue_id
								);
								$device_id[0] = array('device_id' => $value['device_id']);
								$notification = $this->notification->push_notification($device_id, $message, $title, $data_push);

								$to_email = $value['email'];
								$subject = 'Venue Booked';
								$this->load->library('email');
								$config['protocol'] = 'smtp';
								$config['smtp_host'] = 'upupup.in';
								$config['smtp_port'] = '25';
								$config['smtp_timeout'] = '7';
								$config['smtp_user'] = 'admin@upupup.in';
								$config['smtp_pass'] = '%S+1q)yiC@DW';
								$config['charset'] = 'utf-8';
								$config['newline'] = '\r\n';
								$config['mailtype'] = 'html'; // or html
								$config['validation'] = TRUE; // bool whether to validate email or not
								$this->email->initialize($config);
								$this->load->library('email', $config);
								$this->email->set_newline("\r\n");
								$this->email->from('booking@upupup.in', 'upUPUP.');
								$this->email->to($to_email);
								$this->email->subject($subject);
								$data['data']['Coplayer'] = '1';
								$message = $this->load->view('booking_upcoin_mail', $data, true);
								$this->email->message($message);
								$this->email->send();

							}
						}
						$data['data']['Coplayer'] = '';

						if (!empty($venue_managers)) {
							foreach ($venue_managers as $key => $value) {
								if ($players_name) {
									$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]");

								} else {
									$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]");
								}
								//$this->common->sms(str_replace(' ', '', $value['phone']),urlencode($message));

								$subject = 'Venue Booked';
								$to_email = $value['email'];

								$this->load->library('email');
								$config['protocol'] = 'smtp';
								$config['smtp_host'] = 'upupup.in';
								$config['smtp_port'] = '25';
								$config['smtp_timeout'] = '7';
								$config['smtp_user'] = 'admin@upupup.in';
								$config['smtp_pass'] = '%S+1q)yiC@DW';
								$config['charset'] = 'utf-8';
								$config['newline'] = '\r\n';
								$config['mailtype'] = 'html'; // or html
								$config['validation'] = TRUE; // bool whether to validate email or not
								$this->email->initialize($config);
								$this->load->library('email', $config);
								$this->email->set_newline("\r\n");
								$this->email->from('booking@upupup.in', 'upUPUP.');
								$this->email->to($to_email);
								$this->email->subject($subject);
								$message = $this->load->view('booking_owner_mail', $data, true);
								$this->email->message($message);
								$this->email->send();
							}
						}

						if (!empty($venue_managers)) {
							foreach ($venue_managers as $key => $value) {
								if ($players_name) {
									$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]");

								} else {
									$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]");
								}
								//$this->common->sms(str_replace(' ', '', $value['phone']),urlencode($message));

							}

							$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));
						}
						//exit;
						foreach ($upupup_phone as $key => $value) {
							if ($players_name) {
								$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $cp_name  $bk_count_text [Booking ID:$booking_id ]");
							} else {
								$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time  , $bk_count_text [Booking ID:$booking_id ]");
							}
							$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));

						}
						foreach ($upupup_mail as $key => $value) {


							$subject = 'Venue Booked';
							$to_email = $value->email;

							$this->load->library('email');
							$config['protocol'] = 'smtp';
							$config['smtp_host'] = 'upupup.in';
							$config['smtp_port'] = '25';
							$config['smtp_timeout'] = '7';
							$config['smtp_user'] = 'admin@upupup.in';
							$config['smtp_pass'] = '%S+1q)yiC@DW';
							$config['charset'] = 'utf-8';
							$config['newline'] = '\r\n';
							$config['mailtype'] = 'html'; // or html
							$config['validation'] = TRUE; // bool whether to validate email or not
							$this->email->initialize($config);
							$this->load->library('email', $config);
							$this->email->set_newline("\r\n");
							$this->email->from('booking@upupup.in', 'upUPUP.');
							$this->email->to($to_email);
							$this->email->subject($subject);
							$message = $this->load->view('booking_upup_upcoin_mail', $data, true);
							$this->email->message($message);
							$this->email->send();
						}


					}

					$result = array(
						'ErrorCode' => 0,
						'Data' => "$booking_id",
						'Message' => "Venue booked"
					);

				} else {
					$result = array(
						'ErrorCode' => 1,
						'Data' => "Failed",
						'Message' => ""
					);

				}

				/* ------------------------------------------------------ booking using up coin end    -------------------------------------------------------------- */

			} elseif ($payment_mode == 3) {

				/* --------------------------------------------- booking using up coin and payment gateway start ---------------------------------------------------- */
				$booking_id = new DateTime();
				$booking_id = $user_id + $booking_id->getTimestamp();

				$data = array(
					'user_id' => $user_id,
					'sports_id' => $sports_id,
					'date' => $date,
					'court_id' => $court_id,
					'venue_id' => $venue_id,
					'cost' => $cost,
					'booking_id' => $booking_id,
					'payment_mode' => '2',
					'coupon_id' => '0',
					'offer_value' => $offer,
					'price' => $price,
					'bal' => $balance,
					'time' => date('Y-m-d H:i:s')
				);

				$add = $this->venue_model->add_booking('venue_booking', $data);

				foreach ($offer_id as $key => $value1) {
					$offer_id = $value1->offer_id;
					$offer_type = $value1->offer_type;

					if ($offer_type == 1) {
						$data = array(
							'booking_id' => $booking_id,
							'offer_id' => $offer_id,
						);
						$this->venue_model->add_booking('booking_offer', $data);
					}

					if ($offer_type == 2) {
						$data = array(
							'booking_id' => $booking_id,
							'hot_offer_id' => $offer_id,
						);
						$this->venue_model->add_booking('booking_hot_offer', $data);
					}
				}

				foreach ($court_time as $key => $value) {
					$data = array(
						'booking_id' => $booking_id,
						'court_time' => date("H:i:s", strtotime($value)),
						'court_id' => $court_id,
						'capacity' => $cap[$key],
						'date' => $date
					);
					$this->venue_model->add_booking('venue_booking_time', $data);
				}
				if ($add) {
					if (!empty($co_players)) {

						foreach ($co_players as $key => $value) {
							$data = array(
								'booking_id' => $booking_id,
								'court_id' => $court_id,
								'date' => $date,
								'user_id' => $value

							);
							$this->venue_model->add_booking('venue_players', $data);

							$co_player_id = $value;
							$coplayer_sports = $this->venue_model->get_coplayer_sports($sports_id, $co_player_id);
							if (!empty($coplayer_sports)) {

								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $value,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);

							}



						}

					}

					if (!empty($co_players_contact)) {

						foreach ($co_players_contact as $key => $value1) {
							$contact_name = $value1->contact_name;
							$contact_number = $value1->contact_number;

							$user_check = $this->venue_model->get_usercheck($contact_number);

							if (!empty($user_check)) {

								foreach ($user_check as $key => $value2) {
									$existing_user = $value2->id;
								}

								$data = array(
									'booking_id' => $booking_id,
									'court_id' => $court_id,
									'date' => $date,
									'user_id' => $existing_user

								);
								$this->venue_model->add_booking('venue_players', $data);
								array_push($co_players, $existing_user);
								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $existing_user,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);
							} else {
								$data = array(
									'phone_no' => $contact_number,
									'name' => $contact_name,
									'status' => '1',
								);
								$this->venue_model->add_booking('users', $data);

								$user_check = $this->venue_model->get_usercheck($contact_number);
								foreach ($user_check as $key => $value2) {
									$new_user = $value2->id;
								}

								$data = array(
									'user_id' => $new_user,
									'sports_id' => $sports_id,
								);
								$this->venue_model->add_booking('user_sports', $data);
								$data = array(
									'booking_id' => $booking_id,
									'court_id' => $court_id,
									'date' => $date,
									'user_id' => $new_user

								);
								$this->venue_model->add_booking('venue_players', $data);
								array_push($co_players, $new_user);
								$data3 = array(
									'user_id' => $user_id,
									'co_player' => $new_user,
									'sports_id' => $sports_id,
									'rating' => 0
								);
								$this->venue_model->insert_coplayer($data3);
							}

						}

					}
					if (!empty($co_players)) {

						$output = $this->sampling($co_players, 2, $booking_id);
					}


					$result = array(
						'ErrorCode' => 0,
						'Data' => "$booking_id",
						'Message' => "Venue booked"
					);

				} else {
					$result = array(
						'ErrorCode' => 1,
						'Data' => "Failed",
						'Message' => ""
					);

				}

				/* --------------------------------------------- booking using up coin and payment gateway end   ---------------------------------------------------- */

			}

		} else {
			foreach ($court_time as $key => $value) {
				$this->venue_model->delete_court_book($court_id, $value, $date);
			}
			$result = array(
				'ErrorCode' => 1,
				'Data' => "",
				'Message' => "Already Booked"
			);
		}
		return $this->response($result, 200);
	}


	//////////////////////////***************************************************************************************************************************///////////////////////

	/* ######################################################################################################################################################################  */

	public function booking_payment_demo_post()
	{

		$user_id = $this->input->post('user_id');
		$booking_id = $this->input->post('booking_id');
		$transaction_id = $this->input->post('transaction_id');
		$payment_id = $this->input->post('payment_id');
		$payment_mode = $this->input->post('payment_mode');
		$coupon_id = $this->input->post('coupon_id');
		$court_id = $this->input->post('court_id');
		$court_timing = json_decode($this->input->post('court_time'));
		$date = $this->input->post('date');
		$share_location = $this->input->post('share_location');
		$payment_type = $this->input->post('payment_type');
		$upcoin_setting_id = $this->input->post('upcoin_setting_id');
		$rupee = $this->input->post('rupee');
		$coin = $this->input->post('coin');


		if ($payment_type == 1) {


			foreach ($court_timing as $key => $value) {
				$this->venue_model->delete_court_book($court_id, $value, $date);
			}

			$players_details = [];
			$players_name = [];

			$booking_details = $this->venue_model->get_venue_booking($booking_id);
			$hosted_user_details = $this->venue_model->users_list($booking_details->user_id);

			$players = $this->venue_model->get_venue_players($booking_id, $booking_details->user_id);

			foreach ($players as $key => $value) {
				$players_details[] = $this->venue_model->users_list($value['user_id']);
			}
			//print_r($players_details);exit;

			if ($coupon_id != '') {
				$coupon_array = array(
					'user_id' => $booking_details->user_id,
					'coupon_id' => $coupon_id,
					'booking_id' => $booking_id
				);
				$used_coupon = $this->venue_model->used_coupon_insert($coupon_array);
			}

			$venue_managers = $this->venue_model->venue_managers($booking_details->venue_id);

			$upupup_mail = $this->venue_model->up_users_mail();

			$upupup_phone = $this->venue_model->up_users_phone();


			foreach ($court_timing as $key => $value) {

				$court_time[] = date('h:i a', strtotime($value));
			}
			foreach ($players_details as $key => $value) {

				if ($value['name'] == "upUPUP User") {
					$players_name[] = $value['name'] . " ( " . $value['phone_no'] . " )";
				} else {
					$players_name[] = $value['name'];
				}
			}
			$cp_name = "";
			$cp_phone = "";
			foreach ($players_details as $key => $value) {

				$pla_name = $value['name'];
				$cp_phone = $value['phone_no'];
				$cpd_name = $cp_name . $pla_name . "(" . $cp_phone . ")";
				$cp_name = $cpd_name . ",";
			}
			foreach ($court_time as $key => $value) {

				$time = "$value";
				$time2 = "$booking_details->intervel minutes";
				$timestamp = strtotime($time . " +" . $time2);


				$Tcourt_time[] = $value . " - " . date("h:i a", $timestamp);
			}

			$Tcourt_time = implode(', ', $Tcourt_time);
			$court_time = implode(', ', $court_time);

			$players_name = implode(', ', $players_name);

			if (empty($payment_id)) {
				$data = array(
					'transaction_id' => $transaction_id,
					'payment_mode' => $payment_mode,
					'coupon_id' => $coupon_id,
					'status' => 1
				);
			} else {
				$data = array(
					'transaction_id' => $transaction_id,
					'payment_id' => $payment_id,
					'payment_mode' => $payment_mode,
					'coupon_id' => $coupon_id,
					'status' => 1
				);
			}



			$update_booking = $this->venue_model->update_booking($data, $booking_id);
			if ($share_location == 1) {

				$venue_location = $this->venue_model->get_venue_location($booking_id);

				foreach ($venue_location as $key => $value1) {
					$lat = $value1->lat;
					$long = $value1->lon;

				}
			}





			/* ---------------------------------------------------------- booking bonus start -------------------------------------------------------------------------------*/

			$data = array(
				'booking_id' => $booking_id,
				'payment_mode_id' => $payment_type,
				'upcoin_setting_id' => $upcoin_setting_id,
				'rupee' => $rupee,
				'coin' => $coin,
				'date' => date('Y-m-d'),
				'added_date' => date('Y-m-d H:i:s'),
			);

			$add = $this->venue_model->add_booking('booking_payment_mode', $data);

			$book_bonus_applied = $this->venue_model->get_book_bonus_applied($user_id);
			if (empty($book_bonus_applied)) {
				/*############################### if initial booking bonus not applied start ############################*/

				$first_booking = $this->venue_model->get_first_booking($user_id);
				foreach ($first_booking as $row) {
					$booking_count = $row->booking_count;
				}
				if ($booking_count == 1) {
					$user_location = $this->venue_model->get_user_locationbok($user_id);
					foreach ($user_location as $row) {
						$location_id = $row->location_id;
					}
					$check_book_setting = $this->venue_model->get_check_book_setting($location_id);
					if (!empty($check_book_setting)) {

						foreach ($check_book_setting as $row) {
							$bkset_id = $row->id;
							$bkset_coin = $row->coin;
						}
						$my_account = $this->venue_model->get_my_account($user_id);
						if (!empty($my_account)) {

							foreach ($my_account as $row) {
								$up_coin = $row->up_coin;
								$bonus_coin = $row->bonus_coin;
								$total = $row->total;
							}
							$bonus_coin = $bonus_coin + $bkset_coin;
							$total = $total + $bkset_coin;
							$update_data = array(
								'bonus_coin' => $bonus_coin,
								'total' => $total
							);
							$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
							$data = array(
								'booking_id' => $booking_id,
								'users_id' => $user_id,
								'booking_bonus_setting_id' => $bkset_id,
								'bonus_coin' => $bkset_coin,
								'date' => date('Y-m-d'),
								'added_date' => date('Y-m-d H:i:s'),
							);

							$this->venue_model->add_booking('booking_bonus', $data);

						} else {
							$up_coin = 0;
							$bonus_coin = 0;
							$total = 0;
							$bonus_coin = $bonus_coin + $bkset_coin;
							$total = $total + $bkset_coin;

							$data = array(
								'users_id' => $user_id,
								'up_coin' => $up_coin,
								'bonus_coin' => $bonus_coin,
								'total' => $total,
								'added_date' => date('Y-m-d H:i:s'),
							);

							$this->venue_model->add_booking('my_account', $data);
							$data = array(
								'booking_id' => $booking_id,
								'users_id' => $user_id,
								'booking_bonus_setting_id' => $bkset_id,
								'bonus_coin' => $bkset_coin,
								'date' => date('Y-m-d'),
								'added_date' => date('Y-m-d H:i:s'),
							);

							$this->venue_model->add_booking('booking_bonus', $data);
						}
					}

					/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ refered friend booking bonus start $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$*/
					$user_location = $this->venue_model->get_user_locationbok($user_id);
					foreach ($user_location as $row) {
						$location_id = $row->location_id;
					}
					$referal_booking_bonus_set = $this->venue_model->get_referal_booking_bonus_set($location_id);
					if (!empty($referal_booking_bonus_set)) {
						foreach ($referal_booking_bonus_set as $row) {
							$refer_book_set_id = $row->id;
							$refer_booking_bonus_coin = $row->booking_bonus_coin;
						}
						$referal_details = $this->venue_model->get_referal_details($user_id);
						if (!empty($referal_details)) {
							foreach ($referal_details as $row) {
								$refered_id = $row->id;
								$refered_user_id = $row->users_id;
								$refered_referal_id = $row->referral_id;
							}
							$user_referal_checks = $this->venue_model->get_user_referal_checks($refered_user_id, $user_id);
							$booking_bonus_applied = $this->venue_model->get_booking_bonus_applied($refered_user_id, $user_id);
							if (!empty($user_referal_checks) && empty($booking_bonus_applied)) {
								$my_account = $this->venue_model->get_my_account($refered_user_id);

								if (!empty($my_account)) {

									foreach ($my_account as $row) {
										$up_coin = $row->up_coin;
										$bonus_coin = $row->bonus_coin;
										$total = $row->total;
									}
									$bonus_coin = $bonus_coin + $refer_booking_bonus_coin;
									$total = $total + $refer_booking_bonus_coin;
									$update_data = array(
										'bonus_coin' => $bonus_coin,
										'total' => $total
									);
									$this->venue_model->update_my_account($update_data, 'my_account', $refered_user_id);
									$data = array(
										'booking_id' => $booking_id,
										'user_id' => $refered_user_id,
										'booked_user_id' => $user_id,
										'refer_bonus_setting_id' => $refer_book_set_id,
										'bonus_coin' => $refer_booking_bonus_coin,
										'date' => date('Y-m-d'),
										'added_date' => date('Y-m-d H:i:s'),
									);
									$add = $this->venue_model->add_booking('referal_booking_bonus', $data);
								} else {
									$up_coin = 0;
									$bonus_coin = 0;
									$total = 0;
									$bonus_coin = $bonus_coin + $refer_booking_bonus_coin;
									$total = $total + $refer_booking_bonus_coin;
									$data = array(
										'users_id' => $refered_user_id,
										'up_coin' => $up_coin,
										'bonus_coin' => $bonus_coin,
										'total' => $total,
										'added_date' => date('Y-m-d H:i:s'),
									);
									$this->venue_model->add_booking('my_account', $data);
									$data = array(
										'booking_id' => $booking_id,
										'user_id' => $refered_user_id,
										'booked_user_id' => $user_id,
										'refer_bonus_setting_id' => $refer_book_set_id,
										'bonus_coin' => $refer_booking_bonus_coin,
										'date' => date('Y-m-d'),
										'added_date' => date('Y-m-d H:i:s'),
									);
									$add = $this->venue_model->add_booking('referal_booking_bonus', $data);
								}
							}

						}
					}
					/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ refered friend booking bonus end $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$*/

				}
				/* ############################if initial booking bonus not applied end #####################*/

			}



			/* ------------------------------------------------------------------- booking bonus end -----------------------------------------------------------------------*/
			$payment_mode_details = $this->venue_model->get_payment_mode_bycash($booking_id);
			foreach ($payment_mode_details as $row) {
				$booking_rupee = $row->rupee;
				$booking_coin = $row->coin;
			}


			$bk_count_text = "";
			$booking_capacity_count = $this->venue_model->get_booking_capacity_count($booking_id);
			foreach ($booking_capacity_count as $row) {
				$booking_count = $row->capacity;

				if ($booking_count > 1) {
					$booking_no_player = "No. of Players : " . $booking_count . " ,";
				} else {
					$booking_no_player = "";
				}
				$bk_count_text = $bk_count_text . $booking_no_player;
			}
			///sms////////////////////////////////
			if (!empty($booking_details)) {
				$booking_service_charge = $this->venue_model->get_booking_service_charge($booking_id);
				if (empty($booking_service_charge)) {
					$bk_service_charge = 0;
				} else {
					foreach ($booking_service_charge as $row) {
						$bk_service_charge = $row->total_service_charge;
					}
				}
				if ($share_location == 1) {
					if ($players_name) {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ] \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long";
					} else {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ] \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long";
					}
				} else {

					if ($players_name) {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]";
					} else {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]";
					}

				}


				$this->common->sms(str_replace(' ', '', $hosted_user_details['phone_no']), urlencode($message));

				$title = "New Venue Booked";
				$data_push = array(
					'result' => array(
						'message' => $message,
						'title' => $title,
						'type' => 6
					),
					'status' => "true",
					'type' => "GENERAL",
					'venue_id' => $booking_details->venue_id
				);

				$notification = $this->notification->push_notification(array($hosted_user_details), $message, $title, $data_push);


				$gmap_link = "http://maps.google.com/maps?q=$lat,$long";
				$data['data'] = array(
					'user' => $hosted_user_details,
					'booking' => $booking_details,
					'court_timing' => $court_time,
					'co_players' => $players_name,
					'mode' => $payment_mode,
					'coupon' => $this->venue_model->get_coupon($coupon_id),
					'rupee' => $booking_rupee,
					'coin' => $booking_coin,
					'service_charge' => (int) $bk_service_charge,
					'no_players' => $bk_count_text,
					'map_link' => $gmap_link,
					'share_location' => $share_location,
					'end_time' => $Tcourt_time

				);


				$to_email = $hosted_user_details['email'];
				$subject = 'Venue Booked';
				$this->load->library('email');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'upupup.in';
				$config['smtp_port'] = '25';
				$config['smtp_timeout'] = '7';
				$config['smtp_user'] = 'admin@upupup.in';
				$config['smtp_pass'] = '%S+1q)yiC@DW';
				$config['charset'] = 'utf-8';
				$config['newline'] = '\r\n';
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not
				$this->email->initialize($config);
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				$this->email->from('booking@upupup.in', 'upUPUP.');
				$this->email->to($to_email);
				$this->email->subject($subject);

				$message = $this->load->view('booking_normal_mail', $data, true);
				$this->email->message($message);
				$this->email->send();
				//print_r($hosted_user_details);exit;

				if (!empty($players_details)) {

					foreach ($players_details as $key => $value) {

						if ($share_location == 1) {

							if (!empty($value['device_id'])) {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ]\r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long");
							} else {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ]\r\n For exciting offers, install the upUPUP android app from Google play store https://play.google.com/store/apps/details?id=com.planetpriorities.upupup \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long");
								$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));
							}
						} else {

							if (!empty($value['device_id'])) {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ] ");
							} else {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ] \r\n For exciting offers, install the upUPUP android app from Google play store https://play.google.com/store/apps/details?id=com.planetpriorities.upupup");
								$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));
							}
						}



						$title = "New Venue Booked";
						$data_push = array(
							'result' => array(
								'message' => $message,
								'title' => $title,
								'type' => 6
							),
							'status' => "true",
							'type' => "GENERAL",
							'venue_id' => $booking_details->venue_id
						);
						$device_id[0] = array('device_id' => $value['device_id']);
						$notification = $this->notification->push_notification($device_id, $message, $title, $data_push);

						$to_email = $value['email'];
						$subject = 'Venue Booked';
						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = 'upupup.in';
						$config['smtp_port'] = '25';
						$config['smtp_timeout'] = '7';
						$config['smtp_user'] = 'admin@upupup.in';
						$config['smtp_pass'] = '%S+1q)yiC@DW';
						$config['charset'] = 'utf-8';
						$config['newline'] = '\r\n';
						$config['mailtype'] = 'html'; // or html
						$config['validation'] = TRUE; // bool whether to validate email or not
						$this->email->initialize($config);
						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from('booking@upupup.in', 'upUPUP.');
						$this->email->to($to_email);
						$this->email->subject($subject);
						$data['data']['Coplayer'] = '1';
						$message = $this->load->view('booking_normal_mail', $data, true);
						$this->email->message($message);
						$this->email->send();

					}
				}
				$data['data']['Coplayer'] = '';

				if (!empty($venue_managers)) {
					foreach ($venue_managers as $key => $value) {
						if ($players_name) {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]");

						} else {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]");
						}
						//$this->common->sms(str_replace(' ', '', $value['phone']),urlencode($message));

						$subject = 'Venue Booked';
						$to_email = $value['email'];

						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = 'upupup.in';
						$config['smtp_port'] = '25';
						$config['smtp_timeout'] = '7';
						$config['smtp_user'] = 'admin@upupup.in';
						$config['smtp_pass'] = '%S+1q)yiC@DW';
						$config['charset'] = 'utf-8';
						$config['newline'] = '\r\n';
						$config['mailtype'] = 'html'; // or html
						$config['validation'] = TRUE; // bool whether to validate email or not
						$this->email->initialize($config);
						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from('booking@upupup.in', 'upUPUP.');
						$this->email->to($to_email);
						$this->email->subject($subject);
						$message = $this->load->view('booking_owner_mail', $data, true);
						$this->email->message($message);
						$this->email->send();
					}
				}

				if (!empty($venue_managers)) {
					foreach ($venue_managers as $key => $value) {
						if ($players_name) {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]");

						} else {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]");
						}
						//$this->common->sms(str_replace(' ', '', $value['phone']),urlencode($message));

					}
					$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));
				}
				//exit;
				foreach ($upupup_phone as $key => $value) {
					if ($players_name) {
						$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $cp_name  $bk_count_text [Booking ID:$booking_id ]");
					} else {
						$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time  , $bk_count_text [Booking ID:$booking_id ]");
					}
					$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));

				}
				foreach ($upupup_mail as $key => $value) {


					$subject = 'Venue Booked';
					$to_email = $value->email;

					$this->load->library('email');
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = 'upupup.in';
					$config['smtp_port'] = '25';
					$config['smtp_timeout'] = '7';
					$config['smtp_user'] = 'admin@upupup.in';
					$config['smtp_pass'] = '%S+1q)yiC@DW';
					$config['charset'] = 'utf-8';
					$config['newline'] = '\r\n';
					$config['mailtype'] = 'html'; // or html
					$config['validation'] = TRUE; // bool whether to validate email or not
					$this->email->initialize($config);
					$this->load->library('email', $config);
					$this->email->set_newline("\r\n");
					$this->email->from('booking@upupup.in', 'upUPUP.');
					$this->email->to($to_email);
					$this->email->subject($subject);
					$message = $this->load->view('booking_upup_normal_mail', $data, true);
					$this->email->message($message);
					$this->email->send();
				}



			}
			/*	if(!empty($update_booking)){
									   $result=array(
												   'ErrorCode'=>0,
												   'Data'=>'',
												   'Message'=>"Booking Completed"
										   );
							   }else{
									   $result=array(
											   'ErrorCode'=>1,
											   'Data'=>'',
											   'Message'=>"Booking Failed"
									   );
							   }*/
			$result = array(
				'ErrorCode' => 0,
				'Data' => '',
				'Message' => "Booking Completed"
			);
			return $this->response($result, 200);


		} elseif ($payment_type == 3) {

			foreach ($court_timing as $key => $value) {
				$this->venue_model->delete_court_book($court_id, $value, $date);
			}

			$players_details = [];
			$players_name = [];

			$booking_details = $this->venue_model->get_venue_booking($booking_id);
			$hosted_user_details = $this->venue_model->users_list($booking_details->user_id);

			$players = $this->venue_model->get_venue_players($booking_id, $booking_details->user_id);

			foreach ($players as $key => $value) {
				$players_details[] = $this->venue_model->users_list($value['user_id']);
			}
			if ($coupon_id != '') {
				$coupon_array = array(
					'user_id' => $booking_details->user_id,
					'coupon_id' => $coupon_id,
					'booking_id' => $booking_id
				);
				$used_coupon = $this->venue_model->used_coupon_insert($coupon_array);
			}

			$venue_managers = $this->venue_model->venue_managers($booking_details->venue_id);

			$upupup_mail = $this->venue_model->up_users_mail();

			$upupup_phone = $this->venue_model->up_users_phone();


			foreach ($court_timing as $key => $value) {

				$court_time[] = date('h:i a', strtotime($value));
			}
			foreach ($players_details as $key => $value) {

				if ($value['name'] == "upUPUP User") {
					$players_name[] = $value['name'] . " ( " . $value['phone_no'] . " )";
				} else {
					$players_name[] = $value['name'];
				}
			}

			$cp_name = "";
			$cp_phone = "";
			foreach ($players_details as $key => $value) {

				$pla_name = $value['name'];
				$cp_phone = $value['phone_no'];
				$cpd_name = $cp_name . $pla_name . "(" . $cp_phone . ")";
				$cp_name = $cpd_name . ",";
			}

			foreach ($court_time as $key => $value) {

				$time = "$value";
				$time2 = "$booking_details->intervel minutes";
				$timestamp = strtotime($time . " +" . $time2);


				$Tcourt_time[] = $value . " - " . date("h:i a", $timestamp);
			}

			$Tcourt_time = implode(', ', $Tcourt_time);
			$court_time = implode(', ', $court_time);

			$players_name = implode(', ', $players_name);

			$data = array(
				'transaction_id' => $transaction_id,
				'payment_id' => $payment_id,
				'payment_mode' => $payment_mode,
				'coupon_id' => $coupon_id,
				'status' => 1
			);


			$update_booking = $this->venue_model->update_booking($data, $booking_id);
			if ($share_location == 1) {

				$venue_location = $this->venue_model->get_venue_location($booking_id);

				foreach ($venue_location as $key => $value1) {
					$lat = $value1->lat;
					$long = $value1->lon;

				}
			}


			$my_accounts = $this->venue_model->get_my_account($user_id);
			if (!empty($my_accounts)) {

				foreach ($my_accounts as $row) {
					$up_coin = $row->up_coin;
					$bonus_coin = $row->bonus_coin;
					$total = $row->total;
				}

				if ($bonus_coin == $coin) {
					$bonus_coin = $bonus_coin - $coin;
					$total = $total - $coin;
					$update_data = array(
						'bonus_coin' => $bonus_coin,
						'total' => $total
					);
					$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
				} elseif ($bonus_coin > $coin) {
					$bonus_coin = $bonus_coin - $coin;
					$total = $total - $coin;
					$update_data = array(
						'bonus_coin' => $bonus_coin,
						'total' => $total
					);
					$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
				} elseif ($bonus_coin < $coin && $bonus_coin != 0) {
					$remaining_coin = $coin - $bonus_coin;
					$avail_bonus_coin = $coin - $remaining_coin;
					$bonus_coin = $bonus_coin - $avail_bonus_coin;
					$up_coin = $up_coin - $remaining_coin;
					$total = $total - $coin;
					$update_data = array(
						'bonus_coin' => $bonus_coin,
						'up_coin' => $up_coin,
						'total' => $total
					);
					$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
				} elseif ($bonus_coin < $coin && $bonus_coin == 0) {
					$up_coin = $up_coin - $coin;
					$total = $total - $coin;
					$update_data = array(
						'up_coin' => $up_coin,
						'total' => $total
					);
					$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
				}
			}



			/* ---------------------------------------------------------- booking bonus start -------------------------------------------------------------------------------*/

			$data = array(
				'booking_id' => $booking_id,
				'payment_mode_id' => $payment_type,
				'upcoin_setting_id' => $upcoin_setting_id,
				'rupee' => $rupee,
				'coin' => $coin,
				'date' => date('Y-m-d'),
				'added_date' => date('Y-m-d H:i:s'),
			);

			$add = $this->venue_model->add_booking('booking_payment_mode', $data);

			$book_bonus_applied = $this->venue_model->get_book_bonus_applied($user_id);
			if (empty($book_bonus_applied)) {
				/*############################### if initial booking bonus not applied start ############################*/

				$first_booking = $this->venue_model->get_first_booking($user_id);
				foreach ($first_booking as $row) {
					$booking_count = $row->booking_count;
				}
				if ($booking_count == 1) {
					$user_location = $this->venue_model->get_user_locationbok($user_id);
					foreach ($user_location as $row) {
						$location_id = $row->location_id;
					}
					$check_book_setting = $this->venue_model->get_check_book_setting($location_id);
					if (!empty($check_book_setting)) {

						foreach ($check_book_setting as $row) {
							$bkset_id = $row->id;
							$bkset_coin = $row->coin;
						}
						$my_account = $this->venue_model->get_my_account($user_id);
						if (!empty($my_account)) {

							foreach ($my_account as $row) {
								$up_coin = $row->up_coin;
								$bonus_coin = $row->bonus_coin;
								$total = $row->total;
							}
							$bonus_coin = $bonus_coin + $bkset_coin;
							$total = $total + $bkset_coin;
							$update_data = array(
								'bonus_coin' => $bonus_coin,
								'total' => $total
							);
							$this->venue_model->update_my_account($update_data, 'my_account', $user_id);
							$data = array(
								'booking_id' => $booking_id,
								'users_id' => $user_id,
								'booking_bonus_setting_id' => $bkset_id,
								'bonus_coin' => $bkset_coin,
								'date' => date('Y-m-d'),
								'added_date' => date('Y-m-d H:i:s'),
							);

							$this->venue_model->add_booking('booking_bonus', $data);

						} else {
							$up_coin = 0;
							$bonus_coin = 0;
							$total = 0;
							$bonus_coin = $bonus_coin + $bkset_coin;
							$total = $total + $bkset_coin;

							$data = array(
								'users_id' => $user_id,
								'up_coin' => $up_coin,
								'bonus_coin' => $bonus_coin,
								'total' => $total,
								'added_date' => date('Y-m-d H:i:s'),
							);

							$this->venue_model->add_booking('my_account', $data);
							$data = array(
								'booking_id' => $booking_id,
								'users_id' => $user_id,
								'booking_bonus_setting_id' => $bkset_id,
								'bonus_coin' => $bkset_coin,
								'date' => date('Y-m-d'),
								'added_date' => date('Y-m-d H:i:s'),
							);

							$this->venue_model->add_booking('booking_bonus', $data);
						}
					}

					/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ refered friend booking bonus start $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$*/
					$user_location = $this->venue_model->get_user_locationbok($user_id);
					foreach ($user_location as $row) {
						$location_id = $row->location_id;
					}
					$referal_booking_bonus_set = $this->venue_model->get_referal_booking_bonus_set($location_id);
					if (!empty($referal_booking_bonus_set)) {
						foreach ($referal_booking_bonus_set as $row) {
							$refer_book_set_id = $row->id;
							$refer_booking_bonus_coin = $row->booking_bonus_coin;
						}
						$referal_details = $this->venue_model->get_referal_details($user_id);
						if (!empty($referal_details)) {
							foreach ($referal_details as $row) {
								$refered_id = $row->id;
								$refered_user_id = $row->users_id;
								$refered_referal_id = $row->referral_id;
							}
							$user_referal_checks = $this->venue_model->get_user_referal_checks($refered_user_id, $user_id);
							$booking_bonus_applied = $this->venue_model->get_booking_bonus_applied($refered_user_id, $user_id);
							if (!empty($user_referal_checks) && empty($booking_bonus_applied)) {
								$my_account = $this->venue_model->get_my_account($refered_user_id);

								if (!empty($my_account)) {

									foreach ($my_account as $row) {
										$up_coin = $row->up_coin;
										$bonus_coin = $row->bonus_coin;
										$total = $row->total;
									}
									$bonus_coin = $bonus_coin + $refer_booking_bonus_coin;
									$total = $total + $refer_booking_bonus_coin;
									$update_data = array(
										'bonus_coin' => $bonus_coin,
										'total' => $total
									);
									$this->venue_model->update_my_account($update_data, 'my_account', $refered_user_id);
									$data = array(
										'booking_id' => $booking_id,
										'user_id' => $refered_user_id,
										'booked_user_id' => $user_id,
										'refer_bonus_setting_id' => $refer_book_set_id,
										'bonus_coin' => $refer_booking_bonus_coin,
										'date' => date('Y-m-d'),
										'added_date' => date('Y-m-d H:i:s'),
									);
									$add = $this->venue_model->add_booking('referal_booking_bonus', $data);
								} else {
									$up_coin = 0;
									$bonus_coin = 0;
									$total = 0;
									$bonus_coin = $bonus_coin + $refer_booking_bonus_coin;
									$total = $total + $refer_booking_bonus_coin;
									$data = array(
										'users_id' => $refered_user_id,
										'up_coin' => $up_coin,
										'bonus_coin' => $bonus_coin,
										'total' => $total,
										'added_date' => date('Y-m-d H:i:s'),
									);
									$this->venue_model->add_booking('my_account', $data);
									$data = array(
										'booking_id' => $booking_id,
										'user_id' => $refered_user_id,
										'booked_user_id' => $user_id,
										'refer_bonus_setting_id' => $refer_book_set_id,
										'bonus_coin' => $refer_booking_bonus_coin,
										'date' => date('Y-m-d'),
										'added_date' => date('Y-m-d H:i:s'),
									);
									$add = $this->venue_model->add_booking('referal_booking_bonus', $data);
								}
							}

						}
					}
					/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ refered friend booking bonus end $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$*/

				}
				/* ############################if initial booking bonus not applied end #####################*/

			}



			/* ------------------------------------------------------------------- booking bonus end -----------------------------------------------------------------------*/
			$payment_mode_details = $this->venue_model->get_payment_mode_mixed($booking_id);
			foreach ($payment_mode_details as $row) {
				$booking_rupee = $row->rupee;
				$booking_coin = $row->coin;
			}

			$booking_capacity_count = $this->venue_model->get_booking_capacity_count($booking_id);
			foreach ($booking_capacity_count as $row) {
				$booking_count = $row->booking_count;
			}
			if ($booking_count > 1) {
				$bk_count_text = "No. of Players : " . $booking_count . " ,";
			} else {

				$bk_count_text = "";
			}
			///sms////////////////////////////////
			if (!empty($booking_details)) {

				$booking_service_charge = $this->venue_model->get_booking_service_charge($booking_id);
				if (empty($booking_service_charge)) {
					$bk_service_charge = 0;
				} else {
					foreach ($booking_service_charge as $row) {
						$bk_service_charge = $row->total_service_charge;
					}
				}
				if ($share_location == 1) {
					if ($players_name) {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ] \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long";
					} else {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ] \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long";
					}
				} else {

					if ($players_name) {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F  Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]";
					} else {
						$message = "You have booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]";
					}

				}


				$this->common->sms(str_replace(' ', '', $hosted_user_details['phone_no']), urlencode($message));

				$title = "New Venue Booked";
				$data_push = array(
					'result' => array(
						'message' => $message,
						'title' => $title,
						'type' => 6
					),
					'status' => "true",
					'type' => "GENERAL",
					'venue_id' => $booking_details->venue_id
				);

				$notification = $this->notification->push_notification(array($hosted_user_details), $message, $title, $data_push);



				$data['data'] = array(
					'user' => $hosted_user_details,
					'booking' => $booking_details,
					'court_timing' => $court_time,
					'co_players' => $players_name,
					'mode' => $payment_mode,
					'coupon' => $this->venue_model->get_coupon($coupon_id),
					'rupee' => $booking_rupee,
					'service_charge' => (int) $bk_service_charge,
					'coin' => $booking_coin,
					'no_players' => $bk_count_text,
					'end_time' => $Tcourt_time

				);



				$to_email = $hosted_user_details['email'];
				$subject = 'Venue Booked';
				$this->load->library('email');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'upupup.in';
				$config['smtp_port'] = '25';
				$config['smtp_timeout'] = '7';
				$config['smtp_user'] = 'admin@upupup.in';
				$config['smtp_pass'] = '%S+1q)yiC@DW';
				$config['charset'] = 'utf-8';
				$config['newline'] = '\r\n';
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not
				$this->email->initialize($config);
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				$this->email->from('booking@upupup.in', 'upUPUP.');
				$this->email->to($to_email);
				$this->email->subject($subject);

				$message = $this->load->view('booking_normal_mail', $data, true);
				$this->email->message($message);
				$this->email->send();
				//print_r($hosted_user_details);exit;

				if (!empty($players_details)) {

					foreach ($players_details as $key => $value) {

						if ($share_location == 1) {

							if (!empty($value['device_id'])) {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ]\r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long");
							} else {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ]\r\n For exciting offers, install the upUPUP android app from Google play store https://play.google.com/store/apps/details?id=com.planetpriorities.upupup \r\n Location of the above venue is http://maps.google.com/maps?q=$lat,$long");
								$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));
							}
						} else {

							if (!empty($value['device_id'])) {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ] ");
							} else {
								$message = ("$booking_details->name has booked  $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and added you as Coplayer , $bk_count_text [Booking ID:$booking_id ] \r\n For exciting offers, install the upUPUP android app from Google play store https://play.google.com/store/apps/details?id=com.planetpriorities.upupup");
								$this->common->sms(str_replace(' ', '', $value['phone_no']), urlencode($message));
							}
						}



						$title = "New Venue Booked";
						$data_push = array(
							'result' => array(
								'message' => $message,
								'title' => $title,
								'type' => 6
							),
							'status' => "true",
							'type' => "GENERAL",
							'venue_id' => $booking_details->venue_id
						);
						$device_id[0] = array('device_id' => $value['device_id']);
						$notification = $this->notification->push_notification($device_id, $message, $title, $data_push);

						$to_email = $value['email'];
						$subject = 'Venue Booked';
						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = 'upupup.in';
						$config['smtp_port'] = '25';
						$config['smtp_timeout'] = '7';
						$config['smtp_user'] = 'admin@upupup.in';
						$config['smtp_pass'] = '%S+1q)yiC@DW';
						$config['charset'] = 'utf-8';
						$config['newline'] = '\r\n';
						$config['mailtype'] = 'html'; // or html
						$config['validation'] = TRUE; // bool whether to validate email or not
						$this->email->initialize($config);
						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from('booking@upupup.in', 'upUPUP.');
						$this->email->to($to_email);
						$this->email->subject($subject);
						$data['data']['Coplayer'] = '1';
						$message = $this->load->view('booking_normal_mail', $data, true);
						$this->email->message($message);
						$this->email->send();

					}
				}
				$data['data']['Coplayer'] = '';

				if (!empty($venue_managers)) {
					foreach ($venue_managers as $key => $value) {
						if ($players_name) {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]");

						} else {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]");
						}
						//$this->common->sms(str_replace(' ', '', $value['phone']),urlencode($message));

						$subject = 'Venue Booked';
						$to_email = $value['email'];

						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = 'upupup.in';
						$config['smtp_port'] = '25';
						$config['smtp_timeout'] = '7';
						$config['smtp_user'] = 'admin@upupup.in';
						$config['smtp_pass'] = '%S+1q)yiC@DW';
						$config['charset'] = 'utf-8';
						$config['newline'] = '\r\n';
						$config['mailtype'] = 'html'; // or html
						$config['validation'] = TRUE; // bool whether to validate email or not
						$this->email->initialize($config);
						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from('booking@upupup.in', 'upUPUP.');
						$this->email->to($to_email);
						$this->email->subject($subject);
						$message = $this->load->view('booking_owner_mail', $data, true);
						$this->email->message($message);
						$this->email->send();
					}
				}

				if (!empty($venue_managers)) {
					foreach ($venue_managers as $key => $value) {
						if ($players_name) {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $players_name , $bk_count_text [Booking ID:$booking_id ]");

						} else {
							$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time , $bk_count_text [Booking ID:$booking_id ]");
						}
						//$this->common->sms(str_replace(' ', '', $value['phone']),urlencode($message));


					}

					$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));
				}
				//exit;
				foreach ($upupup_phone as $key => $value) {
					if ($players_name) {
						$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time and Teamed Up with $cp_name  $bk_count_text [Booking ID:$booking_id ]");
					} else {
						$message = ("$booking_details->name (" . $hosted_user_details['phone_no'] . ") has booked $booking_details->sports at $booking_details->court of $booking_details->venue, $booking_details->area on  " . date('dS F Y', strtotime($booking_details->date)) . " ," . date('l', strtotime($booking_details->date)) . " ,$Tcourt_time  , $bk_count_text [Booking ID:$booking_id ]");
					}
					$this->common->sms(str_replace(' ', '', $value['phone']), urlencode($message));

				}
				foreach ($upupup_mail as $key => $value) {


					$subject = 'Venue Booked';
					$to_email = $value->email;

					$this->load->library('email');
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = 'upupup.in';
					$config['smtp_port'] = '25';
					$config['smtp_timeout'] = '7';
					$config['smtp_user'] = 'admin@upupup.in';
					$config['smtp_pass'] = '%S+1q)yiC@DW';
					$config['charset'] = 'utf-8';
					$config['newline'] = '\r\n';
					$config['mailtype'] = 'html'; // or html
					$config['validation'] = TRUE; // bool whether to validate email or not
					$this->email->initialize($config);
					$this->load->library('email', $config);
					$this->email->set_newline("\r\n");
					$this->email->from('booking@upupup.in', 'upUPUP.');
					$this->email->to($to_email);
					$this->email->subject($subject);
					$message = $this->load->view('booking_upup_normal_mail', $data, true);
					$this->email->message($message);
					$this->email->send();
				}



			}

			if (!empty($update_booking)) {
				$result = array(
					'ErrorCode' => 0,
					'Data' => '',
					'Message' => "Booking Completed"
				);
			} else {
				$result = array(
					'ErrorCode' => 1,
					'Data' => '',
					'Message' => "Booking Failed"
				);
			}
			return $this->response($result, 200);


		}


	}


	/* ######################################################################################################################################################################  */
	public function booking_service_charge_get()
	{
		$booking_id = "1551251112";
		$booking_service_charge = $this->venue_model->get_booking_service_charge($booking_id);
		if (empty($booking_service_charge)) {
			$bk_service_charge = 0;
		} else {
			foreach ($booking_service_charge as $row) {
				$bk_service_charge = $row->total_service_charge;
			}
		}
		print_r($bk_service_charge);

	}
}
