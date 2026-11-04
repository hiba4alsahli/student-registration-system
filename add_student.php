<?php

session_start();

require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

$name = '';
$email = '';
$phone = '';
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {

        $error = 'يرجى تعبئة جميع الحقول.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'يرجى إدخال بريد إلكتروني صحيح.';

    } else {

        $sql = "SELECT id FROM students
                WHERE email = :email
                LIMIT 1";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'email' => $email
        ]);

        $existing_student = $stmt->fetch();

        if ($existing_student) {

            $error = 'هذا البريد الإلكتروني مستخدم بالفعل.';

        } else {

            $profile_picture = '';

            /*
             * Upload profile picture
             */

            if (isset($_FILES['profile_picture']) &&
                $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {

                if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {

                    $error = 'حدث خطأ أثناء رفع الصورة.';

                } else {

                    $allowed_extensions = [
                        'jpg',
                        'jpeg',
                        'png',
                        'gif'
                    ];

                    $file_extension = strtolower(
                        pathinfo(
                            $_FILES['profile_picture']['name'],
                            PATHINFO_EXTENSION
                        )
                    );

                    if (!in_array($file_extension, $allowed_extensions)) {

                        $error = 'يرجى اختيار صورة بصيغة JPG أو PNG أو GIF.';

                    } else {

                        $upload_folder = 'assets/uploads/';

                        $new_file_name = uniqid() . '.' . $file_extension;

                        $upload_path = $upload_folder . $new_file_name;

                        if (move_uploaded_file(
                            $_FILES['profile_picture']['tmp_name'],
                            $upload_path
                        )) {

                            $profile_picture = $new_file_name;

                        } else {

                            $error = 'تعذر حفظ الصورة.';
                        }
                    }
                }
            }

            /*
             * Insert student
             */

            if (empty($error)) {

                $sql = "INSERT INTO students
                        (name, email, phone, address, profile_picture)
                        VALUES
                        (:name, :email, :phone, :address, :profile_picture)";

                $stmt = $conn->prepare($sql);

                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'profile_picture' => $profile_picture
                ]);

                header('Location: view_student.php?success=1');
                exit();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إضافة طالب</title>

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

                    <?php if (isset($_SESSION['user_id'])): ?>

                    <li class="nav-item">

                        <span class="nav-link">

                            مرحباً،
                            <?php echo htmlspecialchars($_SESSION['username']); ?>

                        </span>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" href="logout.php">

                            تسجيل الخروج

                        </a>

                    </li>

                    <?php else: ?>

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


    <main>

        <section class="py-5">

            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-lg-7">

                        <div class="card shadow-sm">

                            <div class="card-body p-4">

                                <div class="text-center mb-4">

                                    <h2>
                                        إضافة طالب
                                    </h2>

                                    <p class="text-muted">
                                        أدخل بيانات الطالب لإضافته إلى النظام.
                                    </p>

                                </div>


                                <?php if (!empty($error)): ?>

                                <div class="alert alert-danger">
                                    <?php echo htmlspecialchars($error); ?>
                                </div>

                                <?php endif; ?>


                                <?php if (!empty($success)): ?>

                                <div class="alert alert-success">
                                    <?php echo htmlspecialchars($success); ?>
                                </div>

                                <?php endif; ?>


                                <form method="POST" action="add_student.php" enctype="multipart/form-data"
                                    id="addStudentForm">

                                    <div class="mb-3">

                                        <label for="name" class="form-label">

                                            اسم الطالب

                                        </label>

                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo htmlspecialchars($name); ?>" required>

                                    </div>


                                    <div class="mb-3">

                                        <label for="email" class="form-label">

                                            البريد الإلكتروني

                                        </label>

                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($email); ?>" required>

                                        <div id="emailMessage" class="form-text text-danger"></div>

                                    </div>


                                    <div class="mb-3">

                                        <label for="phone" class="form-label">

                                            رقم الهاتف

                                        </label>

                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="<?php echo htmlspecialchars($phone); ?>" required>

                                    </div>


                                    <div class="mb-3">

                                        <label for="address" class="form-label">

                                            العنوان

                                        </label>

                                        <textarea class="form-control" id="address" name="address" rows="4"
                                            required><?php echo htmlspecialchars($address); ?></textarea>

                                    </div>


                                    <div class="mb-4">

                                        <label for="profile_picture" class="form-label">

                                            الصورة الشخصية
                                            <span class="text-muted">
                                                (اختياري)
                                            </span>

                                        </label>

                                        <input type="file" class="form-control" id="profile_picture"
                                            name="profile_picture" accept="image/jpeg,image/png,image/gif">

                                    </div>

                                    <div id="ajaxMessage" class="text-danger mb-3"></div>

                                    <div class="d-flex gap-2">

                                        <button type="submit" class="btn btn-success">

                                            إضافة الطالب

                                        </button>

                                        <a href="view_student.php" class="btn btn-outline-secondary">

                                            العودة

                                        </a>

                                    </div>

                                </form>

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

                &copy; 2026 نظام تسجيل الطلاب.
                جميع الحقوق محفوظة.

            </p>

        </div>

    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/main.js"></script>

</body>

</html>