<?php
// File: ltWeb/TourBooking/controllers/AdminController.php

require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/User.php'; // Thêm model User

class AdminController {

    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Chuyển hướng nếu chưa đăng nhập hoặc không phải admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role_id']) || $_SESSION['user_role_id'] != 1) {
            header('Location: /login');
            exit();
        }
    }

    private function renderView($viewName, $data = []) {
        extract($data);
        ob_start();
        require_once __DIR__ . "/../views/admin/{$viewName}.php";
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/admin/layout.php';
    }

    public function index() {
        $this->checkAdmin();
        $this->renderView('dashboard');
    }

    public function listTours() {
        $this->checkAdmin();
        $tourModel = new Tour();
        $tours = $tourModel->getAllTours();
        $this->renderView('tours_list', ['tours' => $tours]);
    }

    public function addTour() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý logic thêm tour
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../public/img/";
                $image = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            $tourModel = new Tour();
            $tourModel->addTour(
                $_POST['title'], $_POST['location'], $_POST['description'], $_POST['price'],
                $_POST['duration_days'], $_POST['start_date'], $_POST['end_date'],
                $_POST['available_slots'], $_POST['stars'], $image, $_POST['loai_tour']
            );
            header('Location: /admin/tours');
            exit();
        }
        $this->renderView('tour_add');
    }
    
    public function editTour() {
        $this->checkAdmin();
        $tourModel = new Tour();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/tours');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $_POST['current_image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../public/img/";
                $image = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            $tourModel->updateTour(
                $id, $_POST['title'], $_POST['location'], $_POST['description'], $_POST['price'],
                $_POST['duration_days'], $_POST['start_date'], $_POST['end_date'],
                $_POST['available_slots'], $_POST['stars'], $image, $_POST['loai_tour']
            );
            header('Location: /admin/tours');
            exit();
        }

        $tour = $tourModel->getTourById($id);
        if (!$tour) {
            header('Location: /admin/tours');
            exit();
        }
        $this->renderView('edit_tour', ['tour' => $tour]);
    }


    public function deleteTour() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $tourModel = new Tour();
            $tourModel->deleteTour($id);
        }
        header('Location: /admin/tours');
        exit();
    }

    public function listUsers() {
        $this->checkAdmin();
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $this->renderView('user_management', ['users' => $users]);
    }

    public function deleteUser() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            // Ngăn việc xóa tài khoản admin
            if ($id == $_SESSION['user_id']) {
                 echo "<script>alert('Bạn không thể xóa chính mình!'); window.location.href='/admin/users';</script>";
                 exit();
            }

            $userModel = new User();
            $userModel->deleteUser($id);
        }
        header('Location: /admin/users');
        exit();
    }
}