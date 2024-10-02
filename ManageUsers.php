<?php
// Tắt hiển thị thông báo lỗi
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_PARSE); // Chỉ hiển thị lỗi nghiêm trọng

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="js/manage_users.js"></script>
    <style>
        /* Thu nhỏ icon bằng CSS nếu cần */
        .btn {
            font-size: 14px; /* Giảm kích thước font của các nút */
        }
        .table img {
            width: 20px;
            height: auto;
        }
        .select_btn {
            font-size: 14px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container mt-4">
    <h1 class="text-center mb-4">THÊM THÔNG TIN HỌC SINH</h1>
    <div class="row">
        <!-- Form Section -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div id="alert" class="alert alert-warning" role="alert" style="display:none;"></div>
                    <form id="userForm">
                        <div class="mb-3">
                            <label for="fingerid" class="form-label">ID Vân tay:</label>
                            <input type="number" class="form-control" name="fingerid" id="fingerid" placeholder="User Fingerprint ID...">
                        </div>
                        <button type="button" class="btn btn-primary mb-3 fingerid_add">Thêm ID</button>

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên học sinh:</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Tên học sinh...">
                        </div>

                        <div class="mb-3">
                            <label for="class" class="form-label">Lớp:</label>
                            <select class="form-select" name="class" id="class">
                                <option value="" disabled selected>Chọn Lớp...</option>
                                <option value="11A1">11A1</option>
                                <option value="11A2">11A2</option>
                                <option value="11A3">11A3</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="number" class="form-label">Số điện thoại:</label>
                            <input type="number" class="form-control" name="number" id="number" placeholder="Số điện thoại...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giới tính:</label><br>
                            <input type="radio" class="gender" name="gender" value="Male" checked> Nam
                            <input type="radio" class="gender" name="gender" value="Female"> Nữ
                        </div>

                        <button type="button" class="btn btn-success mb-3 user_add">Thêm thông tin</button>
                        <button type="button" class="btn btn-warning mb-3 user_upd">Sửa thông tin</button>
                        <button type="button" class="btn btn-danger mb-3 user_rmo">Xóa thông tin</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- User Table Section -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center">Danh sách User Table</h2> <!-- Để nguyên tiêu đề "User Table" -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Vân tay</th>
                                    <th>Tên</th>
                                    <th>Giới tính</th>
                                    <th>Lớp</th>
                                    <th>Ngày</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody id="manage_users">
                                <!-- Dữ liệu sẽ được tải từ manage_users_up.php -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Tải dữ liệu người dùng
    loadUsers();
});

// Hàm tải dữ liệu người dùng
function loadUsers() {
    $.ajax({
        url: "manage_users_up.php"
    }).done(function(data) {
        $('#manage_users').html(data);
    });
}
</script>
</body>
</html>
