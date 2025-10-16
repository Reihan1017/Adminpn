<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Upload extends Controller
{
    public function image()
    {
        $file = $this->request->getfile('upload');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // Nama acak agar unik
            $newName = $file->getRandomName();

            // Pindahkan ke public/themes/modern/upload
            $file->move(FCPATH . 'images/upload', $newName);

            // Kirim URL yang bisa diakses browser
            return $this->response->setJSON([
                'url' => base_url('images/upload/' . $newName)
            ]);
        }
        

        return $this->response->setJSON(['error' => 'Upload gagal'], 400);
    }
}
