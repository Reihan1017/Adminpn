<?php 
namespace App\Modules\Downloadexcel\Controllers;
use App\Modules\Downloadexcel\Models\DownloadExcelModel;

class DownloadExcel extends \App\Controllers\BaseController
{
	protected $model;
	private $maxData = 100000;
	private $usedTable = 'transaksi';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new DownloadExcelModel;	
		$this->data['list_tabel'] = ['mahasiswa' => 'Mahasiswa', 'transaksi' => 'Transaksi'];
			
		if (!empty($_REQUEST['nama_tabel'])) {
			if (key_exists($_REQUEST['nama_tabel'], $this->data['list_tabel'])) {
				$this->usedTable = $_REQUEST['nama_tabel'];
			}
		}
		
	}
	

	public function maxData() {
		echo $this->maxData;
	}
	
	public function countData() {
		echo $this->model->getTotalData($this->usedTable);
	}
	
	public function index()
	{
		$total_data = 0;
		if (key_exists($this->usedTable, $this->data['list_tabel'])) {
			$total_data = $this->model->getTotalData($this->usedTable);
		}
		
		$this->addJs ('var max_data = ' . $this->maxData . ';
											var total_data = ' . $total_data . ';', true);
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/downloadexcel.js' );
		$this->data['site_title'] = 'Eskpor data Excel';
		
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/image-upload.js' );
		
		helper('format');
		
		
		$this->data['used_tabel'] = $this->usedTable;
		$this->data['total_data'] = $total_data;
		$this->data['selisih'] = 0;
		$this->data['title'] = 'Ekspor data Excel';
		$this->data['max_data'] = $this->maxData;
		
		if (isset($_GET['nama_tabel'])) 
		{
			$path = ROOTPATH . 'public/tmp/';
			$form_errors = $this->validateForm();
			$this->data['status'] = 'ok';
			
			if ($form_errors) {
				$this->data['status'] = 'error';
				$this->data['message'] = $form_errors;
			} else {
				
				if (!empty($_GET['data_awal']) || !empty($_GET['data_akhir'])) 
				{
					$_GET['data_awal'] =  preg_replace("/\D/", "", $_GET['data_awal']);
					$_GET['data_akhir'] =  preg_replace("/\D/", "", $_GET['data_akhir']);
				}
												
				// Cek Error Count
				$jml_data = $this->model->getJumlahData($this->data['max_data']);
				
				if (!$jml_data['jml_data']) {
					$this->data['status'] = 'error';
					$this->data['message'] = 'Data tidak ditemukan';
				}
				
				if ($this->data['status'] == 'ok') {

					$this->model->writeExcel($this->maxData);
				}
			}
		}
		
		$this->view('downloadexcel.php', $this->data);
	}
	
	function validateForm() {

		$form_errors = [];
		if (!$_GET['nama_tabel']) {
			$form_errors[] = 'Tabel belum didefinisikan';
		}
		if (!empty($_GET['nama_tabel'])) {
			
			if (@$_GET['data_akhir'] < @$_GET['data_awal']) {
				$form_errors[] = 'Data akhir lebih kecil dari data awal';
			}
			
			if (!key_exists($_GET['nama_tabel'], $this->data['list_tabel'])) {
				$form_errors[] = 'Tabel ' . $_GET['nama_tabel'] . ' tidak diperkenankan';
			}
			
			if (!empty($_GET['data_akhir'])) {
						
				if (@$_GET['data_akhir'] < $this->data['max_data']) {
					$num_rows = $_GET['data_akhir'];
				}
				
				if ($_GET['data_akhir'] > $this->data['total_data'] || $_GET['data_awal'] > $this->data['total_data']) 
				{
					$form_errors[] = $this->data['error_field'] = 'Data awal ('. format_ribuan($_GET['data_awal']) . ') atau data akhir ('. format_ribuan($_GET['data_akhir']) . ') melebih jumlah maksimal data, yaitu <strong> '. format_ribuan($this->data['total_data']) . '</strong>';
				}
				
				
				$this->data['selisih'] = @$_GET['data_akhir'] - @$_GET['data_awal'];
				
				if ($this->data['selisih'] > $this->data['max_data']) {
					$form_errors[] = $this->data['error_field'] = 'Data akhir dikurangi data awal (' . format_ribuan($_GET['data_akhir']) . ' - ' . format_ribuan($_GET['data_awal']) . ') melebih batas yang diperkenankan, yaitu <strong>' . format_ribuan($this->data['max_data']) . '</strong>';
				}
			}
		}
		
		return $form_errors;
	}
}