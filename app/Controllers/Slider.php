<?php
/**
 * Module Slider
 *  Based on Gallery Controller (simplified)
 */

namespace App\Controllers;
use App\Models\SliderModel;

class Slider extends BaseController
{
	protected $model;

	public function __construct()
	{
		parent::__construct();
		$this->model = new SliderModel;
	}

	/**
	 * Tampilkan semua slider
	 */
	public function index()
	{
		$slider = $this->model->getSlider();

		$this->data['title'] = 'Manajemen Slider';
		$this->data['slider'] = $slider;
		$this->data['message'] = session()->getFlashdata('message');

		$this->view('slider-result.php', $this->data);
	}

	/**
	 * Form tambah slider
	 */
	public function add()
	{
		$message = [];

		if ($this->request->getPost('submit')) {
			$save = $this->saveData();
			$message = $save['message'];

			if ($message['status'] == 'ok') {
				return redirect()->to(base_url('slider'))
					->with('message', $message);
			}
		}

		$this->data['title'] = 'Tambah Slider';
		$this->data['slider'] = [];
		$this->data['message'] = $message;

		$this->view('slider-form.php', $this->data);
	}

	/**
	 * Form edit slider
	 */
	public function edit($id)
	{
		$message = [];

		log_message('debug', 'POST DATA: ' . print_r($this->request->getPost(), true));

		if ($this->request->getPost('submit')) {
			$save = $this->saveData(id: $id);
			$message = $save['message'];



			if ($message['status'] == 'ok') {
				return redirect()->to(base_url('slider'))
					->with('message', $message);
			}
		}

		$slider = $this->model->getSliderById($id);
		if (!$slider) {
			return $this->errorDataNotfound();
		}

		$this->data['title'] = 'Edit Slider';
		$this->data['slider'] = $slider;
		$this->data['message'] = $message;

		$this->view('slider-form.php', $this->data);
	}

	/**
	 * Simpan data slider (insert/update)
	 */
	private function saveData($id = null)
	{
		$data = [
			'judul' => $this->request->getPost('judul'),
			'link' => $this->request->getPost('link'),
			'status' => $this->request->getPost('status'),
		];

		// Upload file gambar
		$file = $this->request->getFile('gambar');
		if ($file && $file->isValid() && !$file->hasMoved()) {
			$newName = $file->getRandomName();
			$file->move(FCPATH . 'images/slider/', $newName);
			$data['gambar'] = $newName;
		}

		if ($id) {
			$this->model->update($id, $data);
			return ['status' => 'ok', 'message' => 'Slider berhasil diperbarui'];
		} else {
			$this->model->insert($data);
			return ['status' => 'ok', 'message' => 'Slider berhasil ditambahkan'];
		}
	}


	/**
	 * Hapus slider
	 */
	public function delete($id)
	{
		$result = $this->model->deleteSlider($id);
		return redirect()->to(base_url('slider'))
			->with('message', $result);
	}
}
