<?php
//CONTROLLLER FOR LOGIN
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
        if ($this->isApiRequest()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';
        } else {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
        }

        if (empty($email) || empty($password)) {
            return $this->handleResponse([
                'status' => 'failed',
                'response' => 'All fields required'
            ], 400, 'login');
        }

        $user = $this->userModel->findUserByEmail($email);

        if ($user && password_verify($password, $user->password_hash)) {
            if ((int)$user->is_active === 0) {
                return $this->handleResponse([
                    'status' => 'failed',
                    'response' => 'Account deactivated.'
                ], 403, 'login');
            }
            $_SESSION['User_id'] = $user->User_ID;
            $_SESSION['Employee_ID'] = $user->Employee_ID;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            if (!$this->isApiRequest()) {
                redirect('home');
            } else {
                $this->sendJson([
                    'status' => 'success',
                    'user' => [
                        'id' => $user->User_ID,
                        'username' => $user->username,
                        'employee_id' => $user->Employee_ID,
                        'role' => $user->role,
                    ]
                ]);
            }
        } else {
            return $this->handleResponse([
                'status' => 'failed',
                'response' => 'Invalid credentials'
            ], 401, 'login');
        }
    }
    public function logout() {
        $_SESSION = [];
        if (session_id()) {
            session_destroy();
        }

        if ($this->isApiRequest()) {
            return $this->sendJson([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ]);
        } else {
            redirect('login');
        }
    }
}
