<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\SchoolModel;
use CodeIgniter\RESTful\ResourceController;

class School extends ResourceController
{
    public function explore($school_name = null)
    {
        if ($school_name == null) {
            $postSchool = $_POST['school'];
            return redirect()->to('explore/' . url_title($postSchool));
        }

        $school_name = str_replace('-', ' ', $school_name);
        $link = "https://api-sekolah-indonesia.vercel.app/sekolah/s?sekolah=" . $school_name;

        // $data = file_get_contents($link);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);
        $api_sekolah = curl_exec($ch);
        curl_close($ch);

        $pilihan_sekolah = json_decode($api_sekolah);
        $data_sekolah = $pilihan_sekolah->dataSekolah;
        $data = json_encode($data_sekolah);

        return $this->respond($data);
    }

    public function school($school_name)
    {
        $school_name = str_replace('-', ' ', $school_name);

        $link = "https://api-sekolah-indonesia.vercel.app/sekolah/s?sekolah=" . $school_name;

        // $data = file_get_contents($link);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);
        $api_sekolah = curl_exec($ch);
        curl_close($ch);

        $pilihan_sekolah = json_decode($api_sekolah);
        $data = $pilihan_sekolah->dataSekolah;

        $data = ['data_sekolah' => $data, 'comments' => $this->getBySchool($school_name)];

        return $this->respond($data);
    }

    public function getBySchool($school_name)
    {
        $model = new CommentModel();
        $data = $model->where('school_name', url_title($school_name))->findAll();

        return $data;
    }

    // public function create()
    // {
    //     $model = new SchoolModel();
    //     $data = $this->request->getPost();
    //     foreach ($data as $key => $row) {
    //         $data[$key] = url_title($row);
    //     }

    //     try {
    //         $model->insert($data);
    //     } catch (\Throwable $th) {
    //         return $this->respond($th);
    //     }

    //     return $this->respondCreated(['message' => 'Successfully added']);
    // }
}
