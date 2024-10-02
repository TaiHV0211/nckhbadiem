<?php
error_reporting(E_ERROR | E_PARSE); 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Danh sách học sinh</title>
  <!-- Thêm Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <style>
      .table td, .table th {
          vertical-align: middle;
      }
  </style>
  <script src="js/manage_users.js"></script>
</head>
<body>
<?php include 'header.php'; ?> 
<main class="container mt-4">
  <section>
    <!-- User table -->
    <h1 class="text-center mb-4">Danh sách học sinh</h1>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>ID | Tên học sinh</th>
            <th>Lớp</th>
            <th>Giới tính</th>
            <th hidden>Finger ID</th>
            <th>Ngày</th>
            <th>Thời gian</th>
            <th>Số lần nghỉ</th>
            <th>Số lần đi trễ</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // Kết nối cơ sở dữ liệu
            require 'connectDB.php';

            $sql = "SELECT * FROM users WHERE NOT username='' ORDER BY id DESC";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo '<p class="error">SQL Error</p>';
            } else {
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                if (mysqli_num_rows($resultl) > 0) {
                    while ($row = mysqli_fetch_assoc($resultl)) {
                        // Lấy số lần nghỉ
                        $fingerID = $row['fingerprint_id'];
                        $username = $row['username'];

                        // Đếm số ngày nghỉ 
                        $sql_leave = "
                            SELECT COUNT(DISTINCT checkindate) AS leave_days 
                            FROM users_logs 
                            WHERE username=? AND (timein IS NULL OR timein='')
                        ";
                        $stmt_leave = mysqli_stmt_init($conn);
                        mysqli_stmt_prepare($stmt_leave, $sql_leave);
                        mysqli_stmt_bind_param($stmt_leave, "s", $username);
                        mysqli_stmt_execute($stmt_leave);
                        $leave_result = mysqli_stmt_get_result($stmt_leave);
                        $leave_row = mysqli_fetch_assoc($leave_result);
                        $leave_days = $leave_row['leave_days'];

                        // Đếm số lần đi trễ
                        $sql_late = "
                            SELECT COUNT(*) AS late_days 
                            FROM users_logs 
                            WHERE username=? AND timein > '06:30:00'
                        ";
                        $stmt_late = mysqli_stmt_init($conn);
                        mysqli_stmt_prepare($stmt_late, $sql_late);
                        mysqli_stmt_bind_param($stmt_late, "s", $username);
                        mysqli_stmt_execute($stmt_late);
                        $late_result = mysqli_stmt_get_result($stmt_late);
                        $late_row = mysqli_fetch_assoc($late_result);
                        $late_days = $late_row['late_days'];
          ?>
                    <tr>
                      <td><?php echo $row['id']; echo " | "; echo $row['username']; ?></td>
                      <td><?php echo $row['class']; ?></td>
                      <td><?php echo $row['gender'] == "Male" ? "Nam" : "Nữ"; ?></td>
                      <td hidden><?php echo $row['fingerprint_id']; ?></td>
                      <td><?php echo $row['user_date']; ?></td>
                      <td><?php echo $row['time_in']; ?></td>
                      <td><?php echo $leave_days; ?></td>
                      <td><?php echo $late_days; ?></td>

                    </tr>
          <?php
                    }
                }
            }
          ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<!-- Bootstrap Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Sửa thông tin học sinh</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm">
          <div class="mb-3">
            <label for="edit-id" class="form-label">ID</label>
            <input type="text" class="form-control" id="edit-id" name="id" disabled>
          </div>
          <div class="mb-3">
            <label for="edit-username" class="form-label">Tên học sinh</label>
            <input type="text" class="form-control" id="edit-username" name="username" disabled>
          </div>
          <div class="mb-3">
            <label for="edit-class" class="form-label">Lớp</label>
            <input type="text" class="form-control" id="edit-class" name="class">
          </div>
          <div class="mb-3">
            <label for="edit-gender" class="form-label">Giới tính</label>
            <select class="form-select" id="edit-gender" name="gender">
              <option value="Male">Nam</option>
              <option value="Female">Nữ</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Thêm Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
