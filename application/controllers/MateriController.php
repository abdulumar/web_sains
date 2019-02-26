<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH . '/libraries/JWT.php';
use \Firebase\JWT\JWT;
//use \Firebase\JWT\JWT\SignatureInvalidException;



class MateriController extends CI_Controller {

	private $secret = 'this is key secret';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('materi');

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
		Fungsi untuk mengambil semua materi di database
	*/
	public function get_all()
	{
		return $this->response($this->materi->get());

	}

	/*
		Fungsi untuk mengambil semua materi pada kelas spesifik
	*/
	public function get_kelas($kelas)
	{
		return $this->response($this->materi->get('kelas', $kelas));
	}

	/*
		Fungsi untuk mengambil materi berdasarkan id
	*/
	public function get($id)
	{
		return $this->response($this->materi->get('id', $id));
	}

	/*
		Fungsi untuk memasukan materi baru ke database
	*/
	public function create()
	{
		return $this->response($this->materi->create());
	}

	/*
		Fungsi untuk mengupdate materi berdasarkan id
	*/
	public function update($id)
	{
		$data = $this->get_input();
		return $this->response($this->materi->update($id, $data));
	}

	/*
		Fungsi untuk menghapus materi berdasarkan id
	*/
	public function delete($id)
	{
		return $this->response($this->materi->delete($id));
	}

	/*
		Fungsi untuk mengambil inputan form user untuk digunakan pada metode PUT
	*/
	public function get_input()
	{
		return json_decode(file_get_contents('php://input'));
	}

}
