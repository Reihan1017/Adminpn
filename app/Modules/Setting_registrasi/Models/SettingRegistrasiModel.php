<?php
namespace App\Modules\Setting_registrasi\Models;

class SettingRegistrasiModel extends \App\Models\BaseModel
{
	public function getRole() {
		$sql = 'SELECT * FROM role';
		$query = $this->db->query($sql)->getResultArray();
		return $query;
	}
	
	public function getSettingRegistrasi() {
		$sql = 'SELECT * FROM setting_register';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function saveData() 
	{
		$data_db[] = ['param' => 'enable', 'value' => $_POST['enable'] ];
		$data_db[] = ['param' => 'metode_aktivasi', 'value' => $_POST['metode_aktivasi'] ];
		$data_db[] = ['param' => 'id_role', 'value' => $_POST['id_role'] ];
		
		$this->db->transStart();
		$this->db->table('setting_register')->emptyTable();
		$this->db->table('setting_register')->insertBatch($data_db);
		$query = $this->db->transComplete();
		$query_result = $this->db->transStatus();
		
		if ($query_result) {
			$result['status'] = 'ok';
			$result['message'] = 'Data berhasil disimpan';
		} else {
			$result['status'] = 'error';
			$result['message'] = 'Data gagal disimpan';
		}
		
		return $result;
	}
}
?>