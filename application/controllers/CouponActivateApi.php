<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CouponActivateApi extends CI_Controller {

	public function __construct()
	{
		parent :: __construct();
		$this->load->model('CouponActivateDatabase');
		if($this->session->has_userdata('userauth') == FALSE)
        {
			$this->CouponActivateDatabase->SaltData();
			if($this->session->has_userdata('userauth') == FALSE)
			{
				echo json_encode("error");
				die();
			}
		}
	}

	public function index()
	{
		redirect('CouponActivateApi/CouponActivate');
	}

	public function ViewCart()
	{
		$this->load->view('CouponActivate');
	}

	public function RetrieveCart()
	{
		$data = $this->CouponActivateDatabase->RetrieveCart();
		echo json_encode($data);
	}

	public function CouponActivate()
	{
		$data = $this->CouponActivateDatabase->CouponActivate();
		echo json_encode($data);
	}
	
}
