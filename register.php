<?php
// اتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$password = ""; // لو عامل باسوورد للـ root اكتبه هنا
$dbname = "sabujcha_db";

// إنشاء الاتصال
$conn = new mysqli($host, $user, $password, $dbname);

// فحص الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// لو الفورم اتبعت
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    
    // استلام البيانات من الفورم
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير الباسورد

    // تجهيز وإنشاء الاستعلام
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "✅ تم إنشاء الحساب بنجاح!";
        // تقدر تعمل redirect هنا مثلاً
        // header("Location: login-register.php");
    } else {
        echo "❌ حصل خطأ: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
