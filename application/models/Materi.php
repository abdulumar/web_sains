<?php

Class Materi extends CI_Model {



	public function get($key = null, $value = null)
	{
		if($key != null){
			$query = $this->db->get_where('materi',array($key=>$value));
			return $query->result();
		}

		$query = $this->db->get('materi');
		return $query->result();

	}

	public function create()
	{
		$data = [
			'kelas' 	=> $this->input->post('kelas'),
			'urutan' 	=> $this->input->post('kelas') .'.'. $this->input->post('urutan'),
			'judul' 	=> $this->input->post('judul'),
			'isi' 		=> $this->input->post('isi'),
		];

		if($this->db->insert('materi',$data)){
			return [
				'success' => true,
				'message' => 'Berhasil menambah materi'
			];
		} else {
			return [
				'success' => false,
				'message' => 'Gagal menambah materi'
			];
		}

	}

	public function update($id, $data)
	{
		$data = [
			'kelas' 	=> $data->kelas,
			'urutan' 	=> $data->kelas.'.'.$data->urutan,
			'judul' 	=> $data->judul,
			'isi' 		=> $data->isi,
		];

		$this->db->where('id',$id);
		if($this->db->update('materi', $data)){
			return [
				'success' => true,
				'message' => 'berhasil update materi'
			];
		} else {
			return [
				'success' => false,
				'message' => 'Gagal update materi'
			];
		}

	}

	public function delete($id){
		$this->db->where('id',$id);
		if($this->db->delete('materi')){
			return [
				'success' => true,
				'message' => 'berhasil menghapus materi'
			];
		} else {
			return [
				'success' => false,
				'message' => 'Gagal menghapus materi'
			];
		}
	}


}


?>