<?php

class Login extends Controller {
    public function __construct(){
        $this->userModel = $this->model('User');
    }
    public function index(){
        $data = [
            'title' => 'Login Page',
            'status' => '',
            'response' => '',
        ];

        $this->view('login', $data);
    }
    public function auth(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

//            if empty
            if (empty($email) || empty($password)) {
                return $this->view('login', ['title' => 'Login',
                    'status' => 'failed',
                    'response' => 'All fields required'
                    ]);
            }

            $user = $this->userModel->findUserByEmail($email);

            if ($user) {
                if (password_verify($password, $user->password_hash)) {
                    $_SESSION['id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['role'] = $user->role; // Store the role in the session here
                    redirect('home');
                    exit();
                } else {
                    $this->view('login', [
                        'title' => 'Login',
                        'status' => 'failed' ,
                        'response' => 'Wrong email or password'
                    ]);
                }
            } else {
//                user cant be found
                $this->view('login', [
                    'title' => 'Login',
                    'status' => 'failed',
                    'response' => 'Invalid credentials.'
                ]);
            }
        }
    }
//    public function auth(){
//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            $email = trim($_POST['email']);
//            $password = $_POST['password'];
//
//            $user = $this->userModel->findUserByEmail($email);
//
//            echo "<pre>";
//            if (!$user) {
//                die("Diagnostic: User not found with email: " . $email);
//            }
//
//            if (password_verify($password, $user->password_hash)) {
//                echo "Diagnostic: Password matches! Attempting redirect...<br>";
//                echo "Target URL: " . URLROOT . "/home";
//
//                $_SESSION['id'] = $user->id;
//                $_SESSION['username'] = $user->username;
//
//                // Check if headers are already sent
//                if (headers_sent($file, $line)) {
//                    die("Diagnostic: Cannot redirect. Output started in $file on line $line");
//                }
//
//                redirect('home');
//            } else {
//                die("Diagnostic: Password verify failed. DB Hash: " . $user->password_hash);
//            }
//        }
//    }
    public function logout() {
//        clear all session vars
        $_SESSION = [];
//        destroy session
        session_destroy();
//        go back to login
        redirect('login');
    }
}
