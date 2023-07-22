<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    public function gsign()
    {
        $newData = $this->request->getPost();
        $token = $newData['google_token'];
        require_once Google

        // Get $id_token via HTTPS POST.

        $client = new Google_Client(['client_id' => "74889742184-2dkn63tpgecjde551g8j9lqkn5or0o7t.apps.googleusercontent.com"]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($token);
        if ($payload) {
            $userid = $payload['sub'];
            echo $payload;

            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            // Invalid ID token
        }
        // $token = '';
        // echo $_SERVER;

        // if(isset($_SERVER("GOOGLE_TOKEN"))){
        //     $token = $_SERVER("GOOGLE_TOKEN");
        //     return $this->respond($token);
        // }
        // $google_client = new Google\Client();
        // $google_client->setClientId('74889742184-2dkn63tpgecjde551g8j9lqkn5or0o7t.apps.googleusercontent.com');
        // $google_client->setClientSecret('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');
        // $google_client->setRedirectUri('http://localhost/google/login');
        // $google_client->addScope('email');
        // $google_client->addScope('profile');
    }

    public function login()
    {
        $rules = [
            'username' => 'required|min_length[6]|max_length[50]|alpha_numeric|is_exist[user.username]',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[username,password]',
        ];

        $errors = [
            'username' => [
                'is_exist' => "Username doesn't exist",
            ],
            'password' => [
                'validateUser' => "Username or Password don't match",
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            $data = [
                'validation' => $this->validator->getErrors(),
                'input' => $_POST,
            ];

            return $this->respond($data);
            // session()->setFlashdata('error_login', $this->validator->getErrors());
            // return redirect()->to('login')->withInput();
        }
        $model = new UserModel();

        $user = $model->where('username', $this->request->getVar('username'))->first();

        return $this->respond($this->setUserSession($user));
    }

    public function setUserSession($data)
    {
        session()->set($data);
        return $this->respond(['message' => 'Logged in successfully']);
    }

    public function register()
    {
        $model = new UserModel();
        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'email' => 'required|valid_email',
            'phone_number' => 'required|is_natural|min_length[8]|max_length[12]',
            'username' => 'required|min_length[5]|max_length[255]|alpha_numeric|is_unique[user.username]',
            'school' => 'permit_empty',
            'password' => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'matches[password]',
        ];

        if (!$this->validate($rules)) {
            $data = [
                'validation' => $this->validator->getErrors(),
                'input' => $_POST,
            ];

            return $this->respond($data);
            // session()->setFlashdata('error_register', $this->validator->getErrors());
            // return redirect()->to('register')->withInput();
        }

        $newData = $this->request->getPost();
        $username = $newData['username'];

        $newData['password'] = password_hash($newData['password'], PASSWORD_DEFAULT);
        $newData['avatar'] = "avatars.dicebear.com/api/open-peeps/$username.svg";
        $newData['otp_code'] = rand(111111, 999999);
        $newData['otp_code_exp'] = date('Y-m-d H:i:s', strtotime('+30 minutes', time()));
        $insert = $model->insert($newData);
        $newData['id'] = $insert;
        // $status = $this->setUserSession($newData);
        $data = [
            'data' => $newData,
            'otp_code' => $newData['otp_code'],
        ];

        return $this->respond($data);
    }
}
