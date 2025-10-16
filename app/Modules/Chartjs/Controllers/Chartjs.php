<?php
/**
* Admin Template Codeigniter 4
* Author	: Agus Prawoto Hadi
* Website	: https://jagowebdev.com
* Year		: 2021
*/

namespace App\Modules\Chartjs\Controllers;
use App\Modules\Chartjs\Models\ChartjsModel;

class Chartjs extends \App\Controllers\BaseController
{
	public function __construct() {
		parent::__construct();
		$this->model = new ChartjsModel;
		$this->addJs($this->config->baseURL . 'public/vendors/chartjs/Chart.bundle.min.js');
		$this->addStyle($this->config->baseURL . 'public/vendors/chartjs/Chart.min.css');
	}
	
	public function index()
	{
		
		$list_tahun = [2019, 2020, 2021];
		
		$tahun = '';
		if (empty($_GET['tahun'])) {
			$tahun = 2021;
		}
		
		if (!empty($_GET['tahun']) && in_array($_GET['tahun'], $list_tahun)) {
			$tahun = $_GET['tahun']; 
		}
		
		$this->data['penjualan'] = $this->model->getPenjualan( $tahun );
		$this->data['item_terjual'] = $this->model->getItemTerjual( $tahun );
        $this->data['tahun'] = $tahun;

        $this->data['message']['status'] = 'ok';
        if (empty($this->data['penjualan'])) {
            $this->data['message']['status'] = 'error';
            $this->data['message']['message'] = 'Data tidak ditemukan';
		}
		
		$this->view('chartjs.php', $this->data);
	}
}