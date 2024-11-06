<?php
require_once 'config.php';
// รหัสผ่านที่ถูกแฮชแล้ว
$hashed_password = '$2y$10$RwAaox5Nyw9XN5OAdIYIo.YOEHy40EYYkr8qW.TWTmstgnxGB0yK.'; 

// รหัสผ่านที่ผู้ใช้กรอก (รหัสผ่านแบบ plaintext ที่คุณต้องการตรวจสอบ)
$input_password = 'your_plain_text_password'; // เปลี่ยนเป็นรหัสผ่านที่คุณต้องการตรวจสอบ

// ฟังก์ชันเพื่อตรวจสอบว่ารหัสผ่านที่ผู้ใช้กรอกตรงกับรหัสที่ถูกแฮชหรือไม่
if (password_verify($input_password, $hashed_password)) {
    echo "รหัสผ่านถูกต้อง";
} else {
    echo "รหัสผ่านไม่ถูกต้อง";
}
?>
