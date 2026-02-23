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
    public function auth() {
        // 1. Identify Input Source
        if ($this->isApiRequest()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';
        } else {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
        }

        // 2. Basic Validation
        if (empty($email) || empty($password)) {
            return $this->handleResponse([
                'status' => 'failed',
                'response' => 'All fields required'
            ], 400, 'login');
        }

        // 3. Model Logic
        $user = $this->userModel->findUserByEmail($email);

        // 4. Verification
        if ($user && password_verify($password, $user->password_hash)) {
            if ((int)$user->is_active === 0) {
                return $this->handleResponse([
                    'status' => 'failed',
                    'response' => 'Account deactivated.'
                ], 403, 'login');
            }

            // 5. Success Logic
            if (!$this->isApiRequest()) {
                // Web browser: Start session and redirect
                $_SESSION['User_id'] = $user->User_ID;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $user->role;
                redirect('home');
            } else {
                // API: Return data (usually with a token)
                $this->sendJson([
                    'status' => 'success',
                    'user' => [
                        'id' => $user->User_ID,
                        'username' => $user->username
                    ]
                ]);
            }
        } else {
            // 6. Fail Logic
            return $this->handleResponse([
                'status' => 'failed',
                'response' => 'Invalid credentials'
            ], 401, 'login');
        }
    }
    public function logout() {
        // 1. Clear session data (relevant for browser-based users)
        $_SESSION = [];
        if (session_id()) {
            session_destroy();
        }

        // 2. Determine response type
        if ($this->isApiRequest()) {
            // API clients just need to know the session is cleared on the server
            return $this->sendJson([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ]);
        } else {
            // Standard browser users are redirected to the login page
            redirect('login');
        }
    }
}
