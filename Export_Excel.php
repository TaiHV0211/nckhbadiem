<?php
// Kết nối đến cơ sở dữ liệu
require 'connectDB.php';

$output = '';

if (isset($_POST["To_Excel"])) {

    // Lấy ngày bắt đầu và ngày kết thúc từ người dùng, hoặc sử dụng ngày hiện tại nếu không có
    $start_date = isset($_POST['start_date']) && $_POST['start_date'] != null ? $_POST['start_date'] : date("Y-m-01");
    $end_date = isset($_POST['end_date']) && $_POST['end_date'] != null ? $_POST['end_date'] : date("Y-m-d");

    // Danh sách các ngày lễ (ngày và tháng, không bao gồm năm)
    $holidays = [
        '01-01', // Ngày Tết Dương lịch
        '04-30', // Ngày Giải phóng miền Nam
        '05-01', // Ngày Quốc tế Lao động
        // Thêm các ngày lễ khác ở đây
    ];

    // Tạo danh sách các ngày làm việc từ thứ Hai đến thứ Sáu, bỏ qua ngày lễ
    $workdays = [];
    $current_date = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);

    while ($current_date <= $end_date_obj) {
        // Lấy phần ngày-tháng (dạng "mm-dd")
        $current_day_month = $current_date->format('m-d');

        // Kiểm tra nếu không phải là ngày cuối tuần (thứ Bảy hoặc Chủ Nhật) và không phải ngày lễ
        if ($current_date->format('N') < 6 && !in_array($current_day_month, $holidays)) {
            $workdays[] = $current_date->format('Y-m-d');
        }
        $current_date->modify('+1 day');
    }

    // Truy vấn dữ liệu từ bảng users_logs cho khoảng thời gian được chọn
    $sql = "SELECT * FROM users_logs WHERE checkindate BETWEEN ? AND ? ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $output .= "\xEF\xBB\xBF"; // Thêm BOM UTF-8
        $output .= '
            <table class="table" bordered="1">  
                <tr>
                    <th>ID</th>
                    <th>Tên học sinh</th>
                    <th>Số điện thoại</th>
                    <th>Lớp</th>
                    <th>Ngày vào</th>
                    <th>Thời gian vào</th>
                    <th>Thời gian ra</th>
                    <th>Số lần nghỉ</th>
                    <th>Số lần đi trễ</th>
                </tr>
        ';

        while ($row = $result->fetch_assoc()) {
            $fingerprint_id = $row['fingerprint_id'];
            $username = utf8_encode($row['username']);
            $phonenumber = utf8_encode($row['phonenumber']);
            $class = utf8_encode($row['class']);
            $checkindate = $row['checkindate'];
            $timein = $row['timein'];
            $timeout = $row['timeout'];

            // Tính số ngày đã điểm danh trong các ngày làm việc
            $sql_present = "
                SELECT COUNT(DISTINCT checkindate) AS present_days
                FROM users_logs
                WHERE username = ? AND checkindate BETWEEN ? AND ? AND checkindate IN ('" . implode("','", $workdays) . "')
            ";
            $stmt_present = mysqli_prepare($conn, $sql_present);
            mysqli_stmt_bind_param($stmt_present, "sss", $username, $start_date, $end_date);
            mysqli_stmt_execute($stmt_present);
            $present_result = mysqli_stmt_get_result($stmt_present);
            $present_row = mysqli_fetch_assoc($present_result);
            $present_days = $present_row['present_days'];

            // Tính số lần nghỉ
            $leave_days = count($workdays) - $present_days;
            if ($leave_days < 0) {
                $leave_days = 0;
            }

            // Tính số lần đi trễ
            $sql_late = "
                SELECT COUNT(*) AS late_days
                FROM users_logs
                WHERE username = ? AND checkindate BETWEEN ? AND ? AND timein > '06:30:00' AND checkindate IN ('" . implode("','", $workdays) . "')
            ";
            $stmt_late = mysqli_prepare($conn, $sql_late);
            mysqli_stmt_bind_param($stmt_late, "sss", $username, $start_date, $end_date);
            mysqli_stmt_execute($stmt_late);
            $late_result = mysqli_stmt_get_result($stmt_late);
            $late_row = mysqli_fetch_assoc($late_result);
            $late_days = $late_row['late_days'];

            $output .= '
                <tr> 
                    <td>' . $fingerprint_id . '</td>
                    <td>' . $username . '</td>
                    <td>' . $phonenumber . '</td>
                    <td>' . $class . '</td>
                    <td>' . $checkindate . '</td>
                    <td>' . $timein . '</td>
                    <td>' . $timeout . '</td>
                    <td>' . $leave_days . '</td>
                    <td>' . $late_days . '</td>
                </tr>
            ';
        }

        $output .= '</table>';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename=User_Log_' . $start_date . '_to_' . $end_date . '.xls');

        echo $output;
        exit();
    } else {
        echo "Không có dữ liệu để xuất.";
        exit();
    }
}
?>
