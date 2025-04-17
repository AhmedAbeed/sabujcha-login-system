<?php
session_start();

// إعداد الاتصال
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sabujcha_db";

$conn = new mysqli($host, $user, $password, $dbname);

// فحص الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// لو المستخدم دخل الإيميل والباسورد
if (isset($_POST['email']) && isset($_POST['password'])) {
    
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // البحث عن المستخدم
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // لو فيه نتيجة
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // نتحقق من الباسورد
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            echo "✅ تم تسجيل الدخول بنجاح، أهلاً يا " . $username;
            // header("Location: index.php"); ← تقدر تعمله redirect بعدين
        } else {
            echo "❌ كلمة السر غير صحيحة.";
        }
    } else {
        echo "❌ لا يوجد مستخدم بهذا الإيميل.";
    }

    $stmt->close();
}

$conn->close();
?>
