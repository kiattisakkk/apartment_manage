<?php
require_once "config.php"; // เชื่อมต่อฐานข้อมูล

// กำหนดตัวแปรสำหรับข้อผิดพลาด
$username_err = $password_err = "";

// ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม POST
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // เตรียมคำสั่ง SQL เพื่อค้นหาผู้ใช้ด้วย username ที่กรอกเข้ามา
    $sql = "SELECT id, username, password, Room_number, urole FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // ผูกตัวแปรกับคำสั่งที่เตรียมไว้
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // กำหนดค่าให้ตัวแปร
        $param_username = $username;

        // รันคำสั่ง SQL
        if (mysqli_stmt_execute($stmt)) {
            // เก็บผลลัพธ์ที่ได้จากคำสั่ง SQL
            mysqli_stmt_store_result($stmt);

            // ตรวจสอบว่ามีผลลัพธ์หรือไม่ (มีผู้ใช้ที่มีชื่อ username ที่กรอกเข้ามา)
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // ผูกตัวแปรเพื่อรับข้อมูลจากฐานข้อมูล
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $room_number, $urole);
                if (mysqli_stmt_fetch($stmt)) {
                    // ตรวจสอบว่ารหัสผ่านที่กรอกตรงกับรหัสผ่านที่เข้ารหัสหรือไม่
                    if (password_verify($password, $hashed_password)) {
                        // เริ่ม session สำหรับผู้ใช้
                        session_start();

                        // เก็บข้อมูลใน session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["room_number"] = $room_number;
                        $_SESSION["urole"] = $urole;

                        // เปลี่ยนเส้นทางตามสิทธิ์ของผู้ใช้
                        switch ($urole) {
                            case "1":
                                header("location: Admin/index.php");
                                break;
                            case "2":
                                header("location: technician/index.php");
                                break;
                            case "3":
                                header("location: users/index.php");
                                break;
                            default:
                                echo "Invalid user role";
                                exit;
                        }
                    } else {
                        // แสดงข้อผิดพลาดหากรหัสผ่านไม่ถูกต้อง
                        $password_err = "รหัสผ่านที่คุณป้อนไม่ถูกต้อง";
                    }
                }
            } else {
                // แสดงข้อผิดพลาดหากไม่พบชื่อผู้ใช้งาน
                $username_err = "ไม่พบบัญชีผู้ใช้ดังกล่าว";
            }
        } else {
            echo "มีบางอย่างผิดพลาด กรุณาลองใหม่ภายหลัง";
        }

        // ปิดการใช้ statement
        mysqli_stmt_close($stmt);
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - เจ้าสัว Apartment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="li.css">
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h2><i class="fas fa-building me-2"></i>เจ้าสัว Apartment</h2>
        <p>กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
    </div>
    <?php 
    if (!empty($username_err)) {
        echo '<div class="alert alert-danger">' . $username_err . '</div>';
    }
    if (!empty($password_err)) {
        echo '<div class="alert alert-danger">' . $password_err . '</div>';
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="text" id="username" name="username" placeholder=" " required>
            <label for="username">ชื่อผู้ใช้งาน</label>
            <i class="fas fa-user input-icon"></i>
        </div>
        <div class="form-group">
            <input type="password" id="password" name="password" placeholder=" " required>
            <label for="password">รหัสผ่าน</label>
            <i class="fas fa-lock input-icon"></i>
        </div>
        <button type="submit" class="btn btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
