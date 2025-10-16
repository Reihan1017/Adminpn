<?php
namespace App\Modules\Chartjs\Models;

class ChartjsModel extends \App\Models\BaseModel
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getPenjualan($tahun) {
		
		 $sql = 'SELECT MONTH(tgl_trx) AS bulan, COUNT(id_trx) as JML, SUM(total_harga) total
				FROM penjualan
				WHERE tgl_trx >= "' . $tahun . '-01-01" AND tgl_trx <= "' . $tahun . '-12-31"
				GROUP BY MONTH(tgl_trx)';
		
        $penjualan = $this->db->query($sql, $tahun)->getResultArray();
		return $penjualan;
	}
	
	public function getItemTerjual($tahun) {
		$sql = 'SELECT id_produk, nama, COUNT(id_produk) AS jml
				FROM penjualan_detail
				LEFT JOIN penjualan USING(id_trx)
				LEFT JOIN barang USING(id_produk)
				WHERE tgl_trx >= "' . $tahun . '-01-01" AND tgl_trx <= "' . $tahun . '-12-31"
				GROUP BY id_produk
				ORDER BY jml DESC LIMIT 7';
				
        $item_terjual = $this->db->query($sql)->getResultArray();
		return $item_terjual;
	}
}