<?php

session_start();

require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login']);
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {

        $error = 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني وكلمة المرور.';

    } else {

        $sql = "SELECT * FROM users 
                WHERE username = :login OR email = :login
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['login' => $login]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            header('Location: view_student.php');
            exit;

        } else {

            $error = 'اسم المستخدم أو البريد الإلكتروني أو كلمة المرور غير صحيحة.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تسجيل الدخول - نظام تسجيل الطلاب</title>

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
                        <a class="nav-link active" href="login.php">
                            تسجيل الدخول
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            إنشاء حساب
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <!-- Login Section -->
    <main>

        <section class="py-5">

            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-md-6 col-lg-5">

                        <div class="card shadow-sm">

                            <div class="card-body p-4">

                                <div class="text-center mb-4">

                                    <h2>
                                        تسجيل الدخول
                                    </h2>

                                    <p class="text-muted">
                                        قم بتسجيل الدخول للوصول إلى النظام
                                    </p>

                                </div>


                                <?php if (!empty($error)): ?>

                                <div class="alert alert-danger text-center">
                                    <?php echo htmlspecialchars($error); ?>
                                </div>

                                <?php endif; ?>


                                <form method="POST" action="login.php">

                                    <!-- Username or Email -->
                                    <div class="mb-3">

                                        <label for="login" class="form-label">
                                            اسم المستخدم أو البريد الإلكتروني
                                        </label>

                                        <input type="text" class="form-control" id="login" name="login"
                                            placeholder="أدخل اسم المستخدم أو البريد الإلكتروني" required>

                                    </div>


                                    <!-- Password -->
                                    <div class="mb-3">

                                        <label for="password" class="form-label">
                                            كلمة المرور
                                        </label>

                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="أدخل كلمة المرور" required>

                                    </div>


                                    <!-- Login Button -->
                                    <div class="d-grid mt-4">

                                        <button type="submit" class="btn btn-success">
                                            تسجيل الدخول
                                        </button>

                                    </div>

                                </form>


                                <div class="text-center mt-4">

                                    <p class="mb-0">
                                        ليس لديك حساب؟
                                        <a href="register.php">
                                            إنشاء حساب
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