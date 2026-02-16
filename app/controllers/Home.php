<?php
class Home extends Controller{
    public function __construct(){
        $this->userModel = $this->model('User');
    }
    public function index(){
        $users = $this->userModel->getUsers();

        $data = [
            'title' => 'home',
            'users' => $users,
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
        ];
        $this->view('home', $data);
    }
    public static function userBadgeCheck($role) {
        $baseClasses = "inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset";

        switch ($role) {
            case 'admin':
                return '<span class="' . $baseClasses . ' bg-red-400/10 text-red-400 ring-red-400/20">Admin</span>';
            case 'manager':
                return '<span class="' . $baseClasses . ' bg-amber-400/10 text-amber-400 ring-amber-400/20">Manager</span>';
            case 'employee':
                return '<span class="' . $baseClasses . ' bg-indigo-400/10 text-indigo-400 ring-indigo-400/20">Employee</span>';
            default:
                return '<span class="' . $baseClasses . ' bg-slate-400/10 text-slate-400 ring-slate-400/20">' . htmlspecialchars($role) . '</span>';
        }
    }
}
