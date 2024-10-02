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
  <title>Users Logs</title>
  <!-- Thêm Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <style>
      /* Custom styles for the table */
      .table-wrapper {
          margin-top: 20px;
      }
      .table thead th {
          background-color: #f8f9fa;
          text-align: center;
      }
      .table tbody td {
          text-align: center;
      }
      .form-control {
          margin-bottom: 15px;
      }
  </style>
</head>
<body>
<?php include'header.php'; ?> 

<main class="container mt-4">
  <!-- Form Section -->
  <section class="mb-4">
  <h1 class="text-center mb-4">Danh sách điểm danh</h1>
  <div class="card p-4 shadow">
    <form method="POST" action="Export_Excel.php" class="row g-3">
      <div class="col-md-6">
        <label for="start_date" class="form-label">Ngày bắt đầu</label>
        <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Ngày bắt đầu">
      </div>
      <div class="col-md-6">
        <label for="end_date" class="form-label">Ngày kết thúc</label>
        <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Ngày kết thúc">
      </div>
      <!-- Dòng mới cho các button -->
      <div class="col-md-6">
        <button type="button" name="user_log" id="user_log" class="btn btn-primary w-100">Lựa chọn ngày</button>
      </div>
      <div class="col-md-6">
        <input type="submit" name="To_Excel" class="btn btn-success w-100" value="Export to Excel">
      </div>
    </form>
  </div>
</section>


  <!-- User Logs Table -->
  <section class="table-wrapper">
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên học sinh</th>
            <th>Số điện thoại</th>
            <th>Lớp</th>
            <th hidden>Fingerprint ID</th>
            <th>Ngày vào</th>
            <th>Thời gian vào</th>
            <th>Thời gian ra</th>
            <th>Thời gian trễ</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody id="userslog">
          <!-- Nội dung sẽ được tải bằng AJAX -->
        </tbody>
      </table>
    </div>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function(){
    // Khi người dùng nhấn vào nút "Lựa chọn ngày"
    $(document).on('click', '#user_log', function(){
      var start_date = $('#start_date').val();  // Lấy giá trị ngày bắt đầu
      var end_date = $('#end_date').val();  // Lấy giá trị ngày kết thúc
      
      // Kiểm tra nếu người dùng chưa chọn ngày
      if (!start_date || !end_date) {
        alert('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc');
        return;
      }
      
      // Gửi yêu cầu AJAX để lọc dữ liệu theo ngày
      $.ajax({
        url: 'user_log_up.php',
        type: 'POST',
        data: {
          'log_date': 1,
          'start_date': start_date,
          'end_date': end_date
        },
        success: function(response) {
          $('#userslog').html(response);  // Cập nhật kết quả vào bảng
        }
      });
    });

    // Tải dữ liệu mặc định và tự động cập nhật sau mỗi 5 giây
    function loadUserLogs() {
      $.ajax({
        url: "user_log_up.php",
        type: 'POST',
        data: {
            'select_date': 1,
        },
        success: function(data) {
          $('#userslog').html(data);  // Cập nhật bảng
        }
      });
    }

    // Tải dữ liệu lần đầu
    loadUserLogs();
  });
</script>

</body>
</html>
