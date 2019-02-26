<?php

Class User extends CI_Model {

	/*
		Fungsi untuk mendaftarkan user baru ke database
	*/
	public function save()
	{
		$data = [
			'email' 	=> $this->input->post('email'),
			'password' 	=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
		];

		if($this->db->insert('Users',$data)){
			return [
				'id' 		=> $this->db->insert_id(),
				'success' 	=> true,
				'message' 	=> 'Berhasil register User'
			];
		}
	
	}

	/*
		Fungsi untuk mengambil data user berdasarkan parameter tertentu
	*/
	public function get($key = null, $value = null)
	{

		if($key != null) {
			$query = $this->db->get_where('users',array($key=>$value));
			return $query->row();
		}

		// jika parameter $key = null maka semua data akan dikembalikan
		$query = $this->db->select('id,email,created_at,updated_at')->get('users');
			return $query->result();

	}

	/*
		Fungsi untuk mengecek apakah user terdapat dalam database
	*/
	public function is_valid($parameter)
	{
		if($parameter == 'email'){
			$email = $this->input->post('email');
			$query = $this->get('email', $email);
			if($query){
				return true;
			}	
		} 

		else if($parameter == 'password'){
			$email = $this->input->post('email');

			$password = $this->input->post('password');

			$hash = $this->get('email', $email)->password;

			if(password_verify($password,$hash))
				return true;	

		}

		return false;		

	}

	/*
		Fungsi untuk menghapus user berdasarkan id
	*/
	public function delete($id)
	{

		$this->db->where('id', $id);
		if($this->db->delete('users')){
			return [
				'success' 	=> true,
				'message' 	=> 'Berhasil menghapus User'
			];
		}

	}

	/*
		Fungsi untuk mengubah data user berdasarkan id
	*/
	public function update($id, $data)
	{
		$data = ['email' => $data->email];

		$this->db->where('id', $id);
		if($this->db->update('users', $data)){
			return [
				'success' => true,
				'message' => 'berhasil update user'
			];
		}

	}


}


?>