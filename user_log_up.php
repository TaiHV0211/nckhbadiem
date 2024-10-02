<?php
session_start();
// Connect to database
require 'connectDB.php';

$output = '';

// if (isset($_POST['log_date'])) {
//     if ($_POST['date_sel'] != 0) {
//         $_SESSION['seldate'] = $_POST['date_sel'];
//     } else {
//         $_SESSION['seldate'] = date("Y-m-d");
//     }
// }

// if ($_POST['select_date'] == 1) {
//     $_SESSION['seldate'] = date("Y-m-d");
// } else if ($_POST['select_date'] == 0) {
//     $seldate = $_SESSION['seldate'];
// }

// Lấy khoảng thời gian từ người dùng (nếu có)
$start_date = isset($_POST['start_date']) &&  $_POST['start_date'] != null ? $_POST['start_date'] : date("Y-m-01");
$end_date = isset($_POST['end_date']) &&  $_POST['end_date'] != null  ? $_POST['end_date'] : date("Y-m-d");

$sql = "SELECT * FROM users_logs WHERE checkindate BETWEEN ? AND ? ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
mysqli_stmt_execute($stmt);
$resultl = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultl) > 0) {
    $output .= '
    <table cellpadding="0" cellspacing="0" border="0">
        <tbody>';
    while ($row = mysqli_fetch_assoc($resultl)) {
        // Chuẩn bị dữ liệu cho mỗi hàng
        $fingerprint_id = $row['fingerprint_id'];
        $username = $row['username'];
        $phonenumber = $row['phonenumber'];
        $class = $row['class'];
        $checkindate = $row['checkindate'];
        $timein = $row['timein'];
        $timeout = $row['timeout'];
        
        // Kiểm tra thời gian điểm danh so với 6:30 của ngày checkindate
        $time_late = checkTime($checkindate, $timein, 1);
        $islate = checkTime($checkindate, $timein, 2);
        $output .= "
        <tr>
            <td>$fingerprint_id</td>
            <td>$username</td>
            <td>$phonenumber</td>
            <td>$class</td>
            <td hidden>{$row['id']}</td>
            <td>$checkindate</td>
            <td>$timein</td>
            <td>$timeout</td>
            <td>$time_late</td>
            <td>$islate</td>
        </tr>";
    }
    $output .= '
        </tbody>
    </table>';
} 

echo $output;

// Hàm checkTime để kiểm tra giờ điểm danh so với 6:30 của checkindate
function checkTime($date, $time, $flg) {
    // Tạo đối tượng DateTime cho 6:30 của ngày checkindate
    $datetime = new DateTime($date . ' ' . $time);
    $checkin_630 = new DateTime($date . ' 06:30:00');
    
    // So sánh thời gian và trả về kết quả
    if ($datetime > $checkin_630) {
        $interval = $checkin_630->diff($datetime);
        $minutes_late = $interval->h * 60 + $interval->i;
        if($flg == 1){
          return "Đi trễ: " . $minutes_late . "phút";
        }else if($flg == 2){
          return "Đi trễ" ;
        }
    } else {
        return "Đúng giờ";
    }
}
?>
