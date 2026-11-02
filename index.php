<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>نظام تسجيل الطلاب</title>

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
                        <a class="nav-link active" href="index.php">
                            الرئيسية
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="view_student.php">
                            عرض الطلاب
                        </a>
                    </li>


                    <?php if (isset($_SESSION['user_id'])): ?>

                    <!-- User is logged in -->

                    <li class="nav-item">
                        <span class="nav-link">
                            مرحباً، <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            تسجيل الخروج
                        </a>
                    </li>


                    <?php else: ?>

                    <!-- User is not logged in -->

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            تسجيل الدخول
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            إنشاء حساب
                        </a>
                    </li>

                    <?php endif; ?>

                </ul>

            </div>

        </div>

    </nav>


    <!-- Main Content -->
    <main>

        <!-- Welcome Section -->
        <section class="py-5">

            <div class="container">

                <div class="row align-items-center">

                    <!-- Text -->
                    <div class="col-lg-7 text-center text-lg-start">

                        <h1 class="display-5 fw-bold">
                            مرحباً بك في نظام تسجيل الطلاب
                        </h1>

                        <p class="lead mt-3">
                            نظام بسيط ومنظم لإدارة معلومات الطلاب.
                        </p>

                        <p>
                            يتيح نظام تسجيل الطلاب للمستخدمين المصرح لهم
                            إضافة بيانات الطلاب وعرضها وتعديلها والبحث عنها
                            وإدارتها بسهولة.
                        </p>

                        <div class="mt-4">

                            <a href="login.php" class="btn btn-success me-2">
                                تسجيل الدخول
                            </a>

                            <a href="view_student.php" class="btn btn-outline-success">
                                عرض الطلاب
                            </a>

                        </div>

                    </div>


                    <!-- Logo -->
                    <div class="col-lg-5 text-center mt-4 mt-lg-0">

                        <img src="assets/img/logo.jpeg" alt="شعار نظام تسجيل الطلاب" class="img-fluid home-logo">

                    </div>

                </div>

            </div>

        </section>


        <!-- Features Section -->
        <section class="py-5 bg-light">

            <div class="container">

                <div class="text-center mb-4">

                    <h2>مميزات النظام</h2>

                    <p class="text-muted">
                        أدوات بسيطة لإدارة سجلات الطلاب.
                    </p>

                </div>


                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="card h-100 text-center">

                            <div class="card-body">

                                <h5 class="card-title">
                                    إدارة الطلاب
                                </h5>

                                <p class="card-text">
                                    إضافة وعرض وتعديل وحذف سجلات الطلاب.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="card h-100 text-center">

                            <div class="card-body">

                                <h5 class="card-title">
                                    البحث والتصفح
                                </h5>

                                <p class="card-text">
                                    البحث عن الطلاب وتصفح السجلات باستخدام الصفحات.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="card h-100 text-center">

                            <div class="card-body">

                                <h5 class="card-title">
                                    الدخول الآمن
                                </h5>

                                <p class="card-text">
                                    حسابات مستخدمين وتخزين آمن لكلمات المرور.
                                </p>

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