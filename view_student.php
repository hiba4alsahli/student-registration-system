<?php

session_start();

require_once 'includes/db.php';


/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$search = '';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}


/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/

$allowed_sort_columns = [
    'id',
    'name',
    'email',
    'phone',
    'created_at'
];

$sort = 'id';
$order = 'DESC';

if (isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns)) {
    $sort = $_GET['sort'];
}

if (isset($_GET['order']) && $_GET['order'] === 'ASC') {
    $order = 'ASC';
}


/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

$students_per_page = 10;

$page = 1;

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = (int) $_GET['page'];
}

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $students_per_page;


/*
|--------------------------------------------------------------------------
| Search Condition
|--------------------------------------------------------------------------
*/

$where = '';
$params = [];

if (!empty($search)) {

    $where = "WHERE name LIKE :search
              OR email LIKE :search
              OR phone LIKE :search";

    $params['search'] = '%' . $search . '%';
}


/*
|--------------------------------------------------------------------------
| Count Students
|--------------------------------------------------------------------------
*/

$count_sql = "SELECT COUNT(*) FROM students $where";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute($params);

$total_students = $count_stmt->fetchColumn();

$total_pages = ceil($total_students / $students_per_page);


/*
|--------------------------------------------------------------------------
| Get Students
|--------------------------------------------------------------------------
*/

$sql = "SELECT *
        FROM students
        $where
        ORDER BY $sort $order
        LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', $students_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$students = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Sorting Link
|--------------------------------------------------------------------------
*/

function sortLink($column, $currentSort, $currentOrder, $search)
{
    $newOrder = 'ASC';

    if ($currentSort === $column && $currentOrder === 'ASC') {
        $newOrder = 'DESC';
    }

    return 'view_student.php?sort=' . $column .
           '&order=' . $newOrder .
           '&search=' . urlencode($search);
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>عرض الطلاب - نظام تسجيل الطلاب</title>

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
                        <a class="nav-link active" href="view_student.php">
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

        <section class="py-5">

            <div class="container">

                <!-- Page Title -->
                <div class="text-center mb-4">

                    <h1>
                        قائمة الطلاب
                    </h1>

                    <p class="text-muted">
                        عرض وإدارة سجلات الطلاب
                    </p>

                </div>


                <!-- Search -->
                <div class="d-flex justify-content-center mb-4">

                    <form method="GET" action="view_student.php" class="student-search-form">

                        <div class="input-group">

                            <input type="text" name="search" class="form-control"
                                placeholder="ابحث بالاسم أو البريد الإلكتروني أو رقم الهاتف"
                                value="<?php echo htmlspecialchars($search); ?>">

                            <button type="submit" class="btn btn-success">
                                بحث
                            </button>

                        </div>

                    </form>

                </div>


                <!-- Add Student Button -->
                <div class="text-start mb-3">

                    <a href="add_student.php" class="btn btn-success">
                        إضافة طالب
                    </a>

                </div>


                <!-- Students Table -->
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle text-center">

                        <thead class="table-success">

                            <tr>

                                <th>
                                    <a href="<?php echo sortLink('id', $sort, $order, $search); ?>"
                                        class="text-dark text-decoration-none">
                                        #
                                    </a>
                                </th>

                                <th>
                                    <a href="<?php echo sortLink('name', $sort, $order, $search); ?>"
                                        class="text-dark text-decoration-none">
                                        اسم الطالب
                                    </a>
                                </th>

                                <th>
                                    <a href="<?php echo sortLink('email', $sort, $order, $search); ?>"
                                        class="text-dark text-decoration-none">
                                        البريد الإلكتروني
                                    </a>
                                </th>

                                <th>
                                    <a href="<?php echo sortLink('phone', $sort, $order, $search); ?>"
                                        class="text-dark text-decoration-none">
                                        رقم الهاتف
                                    </a>
                                </th>

                                <th>
                                    العنوان
                                </th>

                                <th>
                                    الصورة
                                </th>

                                <th>
                                    <a href="<?php echo sortLink('created_at', $sort, $order, $search); ?>"
                                        class="text-dark text-decoration-none">
                                        تاريخ الإضافة
                                    </a>
                                </th>

                                <th>
                                    الإجراءات
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php if (count($students) > 0): ?>

                            <?php foreach ($students as $student): ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($student['id']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['phone']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['address']); ?>
                                </td>

                                <td>

                                    <?php if (!empty($student['profile_picture'])): ?>

                                    <img src="<?php echo "assets/uploads/" . htmlspecialchars($student['profile_picture']); ?>"
                                        alt="صورة الطالب" class="student-picture">

                                    <?php else: ?>

                                    <span class="text-muted">
                                        لا توجد صورة
                                    </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student['created_at']); ?>
                                </td>

                                <td>

                                    <a href="update_student.php?id=<?php echo $student['id']; ?>"
                                        class="btn btn-sm btn-warning">
                                        تعديل
                                    </a>

                                    <a href="delete_student.php?id=<?php echo $student['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟');">
                                        حذف
                                    </a>

                                </td>

                            </tr>

                            <?php endforeach; ?>

                            <?php else: ?>

                            <tr>

                                <td colspan="8" class="py-4">
                                    لا توجد سجلات للطلاب.
                                </td>

                            </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>


                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>

                <nav aria-label="صفحات الطلاب">

                    <ul class="pagination justify-content-center mt-4">

                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">

                            <a class="page-link"
                                href="view_student.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>">

                                السابق

                            </a>

                        </li>


                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>

                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">

                            <a class="page-link"
                                href="view_student.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>">

                                <?php echo $i; ?>

                            </a>

                        </li>

                        <?php endfor; ?>


                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">

                            <a class="page-link"
                                href="view_student.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>">

                                التالي

                            </a>

                        </li>

                    </ul>

                </nav>

                <?php endif; ?>

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