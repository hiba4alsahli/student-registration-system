<?php

session_start();

require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_student.php');
    exit;
}

$id = (int) $_GET['id'];

$sql = "SELECT profile_picture
        FROM students
        WHERE id = :id
        LIMIT 1";

$stmt = $conn->prepare($sql);

$stmt->execute([
    'id' => $id
]);

$student = $stmt->fetch();

if (!$student) {
    header('Location: view_student.php');
    exit;
}

$sql = "DELETE FROM students
        WHERE id = :id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    'id' => $id
]);

if (!empty($student['profile_picture'])) {

    $image_path = 'assets/uploads/' . $student['profile_picture'];

    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

header('Location: view_student.php?deleted=1');
exit;

?>