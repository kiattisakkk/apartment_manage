<?php
require_once "config.php";

// Check if the form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get the data from the POST form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $First_name = trim($_POST["First_name"]);
    $Last_name = trim($_POST["Last_name"]);
    $room_number = trim($_POST["room_number"]);
    $urole = $_POST["role"]; 

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert new user data into the database
    $sql = "INSERT INTO users (username, password, First_name, Last_name, room_number, urole) VALUES (?, ?, ?, ?, ?, ?)";

    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_First_name, $param_Last_name, $param_room_number, $param_urole);

        $param_username = $username;
        $param_password = $hashed_password;
        $param_First_name = $First_name;
        $param_Last_name= $Last_name;
        $param_room_number = $room_number;
        $param_urole = $urole;

        if(mysqli_stmt_execute($stmt)){
            echo "สมัครสมาชิกสำเร็จ";
            header("location: login.php");
            exit;
        } else{
            echo "มีบางอย่างผิดพลาด กรุณาลองใหม่ภายหลัง";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="re.css">
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2 class="register-title">ลงทะเบียน</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="First_name" class="form-label">ชื่อ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" id="First_name" name="First_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="Last_name" class="form-label">นามสกุล</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" id="Last_name" name="Last_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="room_number" class="form-label">เลขห้อง</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                            <input type="text" id="room_number" name="room_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">บทบาท</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <select id="role" name="role" class="form-select" required>
                                <option value="" disabled selected>เลือกบทบาท</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                                <option value="3">Technician</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-register">ลงทะเบียน</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>