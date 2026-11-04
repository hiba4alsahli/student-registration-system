<?php

session_start();

require_once 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {

        $error = 'يرجى تعبئة جميع الحقول.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'يرجى إدخال بريد إلكتروني صحيح.';

    } elseif ($password !== $confirm_password) {

        $error = 'كلمتا المرور غير متطابقتين.';

    } else {

        // Check if username or email already exists
        $sql = "SELECT id FROM users
                WHERE username = :username OR email = :email
                LIMIT 1";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'username' => $username,
            'email' => $email
        ]);

        $existing_user = $stmt->fetch();

        if ($existing_user) {

            $error = 'اسم المستخدم أو البريد الإلكتروني مستخدم بالفعل.';

        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (username, email, password)
                    VALUES (:username, :email, :password)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password
            ]);

            header('location:login.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إنشاء حساب - نظام تسجيل الطلاب</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">

        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="index.php">

                <img src="assets/img/logo.jpeg" alt="شعار النظام" class="navbar-logo">

                <span class="me-2">
                    نظام تسجيل الطلاب
                </span>

            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="navbarContent">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            الرئيسية
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="view_student.php">
                            عرض الطلاب
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            تسجيل الدخول
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="register.php">
                            إنشاء حساب
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <!-- Register Section -->
    <main>

        <section class="py-5">

            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-md-6 col-lg-5">

                        <div class="card shadow-sm">

                            <div class="card-body p-4">

                                <div class="text-center mb-4">

                                    <h2>
                                        إنشاء حساب
                                    </h2>

                                    <p class="text-muted">
                                        أنشئ حساباً جديداً للوصول إلى النظام
                                    </p>

                                </div>


                                <?php if (!empty($error)): ?>

                                <div class="alert alert-danger text-center">
                                    <?php echo htmlspecialchars($error); ?>
                                </div>

                                <?php endif; ?>

                                <form method="POST" action="register.php">

                                    <!-- Username -->
                                    <div class="mb-3">

                                        <label for="username" class="form-label">
                                            اسم المستخدم
                                        </label>

                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="أدخل اسم المستخدم" required>

                                    </div>


                                    <!-- Email -->
                                    <div class="mb-3">

                                        <label for="email" class="form-label">
                                            البريد الإلكتروني
                                        </label>

                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="أدخل البريد الإلكتروني" required>

                                    </div>


                                    <!-- Password -->
                                    <div class="mb-3">

                                        <label for="password" class="form-label">
                                            كلمة المرور
                                        </label>

                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="أدخل كلمة المرور" required>

                                    </div>


                                    <!-- Confirm Password -->
                                    <div class="mb-3">

                                        <label for="confirm_password" class="form-label">
                                            تأكيد كلمة المرور
                                        </label>

                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" placeholder="أعد إدخال كلمة المرور" required>

                                    </div>


                                    <!-- Register Button -->
                                    <div class="d-grid mt-4">

                                        <button type="submit" class="btn btn-success">
                                            إنشاء الحساب
                                        </button>

                                    </div>

                                </form>


                                <div class="text-center mt-4">

                                    <p class="mb-0">

                                        لديك حساب بالفعل؟

                                        <a href="login.php">
                                            تسجيل الدخول
                                        </a>

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <!-- Footer -->
    <footer class="bg-success text-white text-center py-3">

        <div class="container">

            <p class="mb-0">
                &copy; 2026 نظام تسجيل الطلاب. جميع الحقوق محفوظة.
            </p>

        </div>

    </footer>


    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>

</body>

</html>