<table class="table table-striped table-hover table-bordered">
    <tbody>
    <?php
    // Connect to database
    require 'connectDB.php';

    $sql = "SELECT * FROM users WHERE del_fingerid=0 ORDER BY id DESC";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo '<p class="error">SQL Error</p>';
    } else {
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if (mysqli_num_rows($resultl) > 0) {
            while ($row = mysqli_fetch_assoc($resultl)) {
    ?>
                <tr>
                    <td>
                        <form style="display: flex; align-items: center;">
                            <?php  
                            if ($row['fingerprint_select'] == 1) {
                                echo "<img style='width: 20px; margin-right: 5px;' src='icons/ok_check.png' title='The selected UID'>";
                            }
                            $fingerid = $row['fingerprint_id'];
                            ?>
                            <button type="button" class="btn btn-outline-primary select_btn" id="<?php echo $fingerid;?>" title="Select this UID"><?php echo $fingerid;?></button>
                        </form>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['user_date']; ?></td>
                    <td><?php echo $row['time_in']; ?></td>
                </tr>
    <?php
            }   
        }
    }
    ?>
    </tbody>
</table>
