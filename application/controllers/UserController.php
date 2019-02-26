<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH . '/libraries/JWT.php';
use \Firebase\JWT\JWT;
//use \Firebase\JWT\JWT\SignatureInvalidException;



class UserController extends CI_Controller {

	private $secret = 'this is key secret';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');

		//==== Allowing CORS ====//
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

	}


	/*
		Fungsi untuk mengembalikan respon dari model dalam bentuk json
	*/	
	public function response($data, $status = 200)
	{
		$this->output
			 ->set_content_type('application/json')
			 ->set_status_header($status)
			 ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ))
			 ->_display();


		exit;

	}

	/*
		Fungsi untuk mendaftarkan user baru ke database
	*/
	public function register()
	{
		return $this->response($this->user->save());
	}

	/*
		Fungsi untuk mengambil semua data user dari database
	*/
	public function get_all()
	{
		return $this->response($this->user->get());

	}

	/*
		Fungsi untuk mengambil data user berdasarkan id
	*/
	public function get($id)
	{
		return $this->response($this->user->get('id', $id));
	}

	/*
		Fungsi untuk login user
		Fungsi ini menghasilkan JWT dari user yang login
	*/
	public function login()
	{

		$date = new DateTime();

		if(!$this->user->is_valid('email')){
			return $this->response([
				'success' => false,
				'message' => 'Email salah'
			]);
		}

		else if(!$this->user->is_valid('password')){
			return $this->response([
				'success' => false,
				'message' => 'Password salah'
			]);	
		}

		$user = $this->user->get('email',$this->input->post('email'));

		//=======encode data========//
		$payload['id'] = $user->id;
		$payload['email'] = $user->email;
		$payload['iat'] = $date->getTimestamp(); // waktu dibuat
		$payload['exp'] = $date->getTimestamp() + 60*60*2; // waktu kadaluarsa = waktu dibuat + detik * menit * jam

		$output['id_token'] = JWT::encode($payload , $this->secret);


		$this->response($output);
			
	}

	/*
		Fungsi untuk men-decode token yang dikirimkan oleh client
	*/
	public function check_token()
	{

		$jwt = $this->input->get_request_header('Authorization');

		try{

			$decoded = JWT::decode($jwt,$this->secret,array('HS256'));

			return $decoded->id;

		} catch(Exception $e){

			return $this->response([
				'success' => false,
				'message' => 'Token salah'
			], 401);

		}
	}

	/*
		Fungsi untuk menghapus user berdasarkan id
	*/
	public function delete($id)
	{
		if($this->verify_user($id)){
			return $this->response($this->user->delete($id));
		}

	}

	/*
		Fungsi untuk mengupdate user berdasarkan id
	*/
	public function update($id)
	{
		if($this->verify_user($id)){
			$data = $this->get_input();
			return $this->response($this->user->update($id, $data));
		}
		
	}

	/*
		Fungsi untuk mencocokan apakah user yang login sama dengan user yang melakukan request
	*/
	public function verify_user($id)
	{
		// cek apakah user login atau tidak
		if($id_from_token = $this->check_token()){

			// orang yang login hanya bisa meng-update datanya sendiri
			if($id_from_token == $id){
				return true;
			}

			else {
				return $this->response([
					'success' => false,
					'message' => 'User yang login berbeda'
				], 403);
			}
		}

	}

	/*
		Fungsi untuk mengambil input form user untuk digunakan pada metode PUT
	*/
	public function get_input()
	{
		return json_decode(file_get_contents('php://input'));
	}


}
