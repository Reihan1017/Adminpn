<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Modules\Login\Controllers;
use App\Modules\Login\Models\LoginModel;
use \Config\App;
use App\Libraries\Auth;

class Login extends \App\Controllers\BaseController
{
	protected $model = '';
	
	public function __construct() {
		parent::__construct();
		$this->model = new LoginModel;	
		$this->data['site_title'] = 'Login ke akun Anda';
		
		helper(['cookie', 'form']);
		
	}
	
	public function index()
	{
		$this->mustNotLoggedIn();
		
		$this->data['status'] = '';
		if ($this->request->getPost('password')) {
			
			$this->login();
			if ($this->session->get('logged_in')) {
				return redirect()->to($this->config->baseURL);
			}
		}
		
		$query = $this->model->getSettingRegistrasi();
		foreach($query as $val) {
			$this->data['setting_registrasi'][$val['param']] = $val['value'];
		}
		
		csrf_settoken();
		echo view('App\Modules\Login\Views\login-page', $this->data);
	}
	
	private function login()
	{
		// Check Token
		$validation_message = csrf_validation();

		// Cek CSRF token
		if ($validation_message) {
			$this->data['status'] = 'error';
			$this->data['message'] = $validation_message['message'];
			return;
		}
		
		$error = false;
		$user = $this->model->checkUser($this->request->getPost('username'));
		if ($user) {
			if ($user['verified'] == 0) {
				$message = 'User belum aktif';
				$error = true;
			}
			
			if (!password_verify($this->request->getPost('password'), $user['password'])) {
				$message = 'Username dan/atau Password tidak cocok';
				$error = true;
			}
			
		} else {
			$message = 'User tidak ditemukan';
			$error = true;
		}
		
		if ($error)
		{
			$this->data['status'] = 'error';
			$this->data['message'] = $message;
			return;
		}
		
		if ($this->request->getPost('remember')) 
		{
			$this->model->setUserToken($user);
		}
		
		$this->session->set('user', $user);
		$this->session->set('logged_in', true);
		$this->model->recordLogin();
	}
	
	public function refreshLoginData() 
	{
		$email = $this->session->get('user')['email'];
		$result = $this->model->checkUser($email);
		$this->session->set('user', $result);
	}
	
	public function logout() 
	{
		$this->model->deleteAuthCookie($this->session->get('user')['id_user']);
		$this->session->destroy();
		// $this->session->stop();
		exit;
		// return redirect()->to($this->config->baseURL . 'login');
	}
}
