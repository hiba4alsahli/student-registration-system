<?php

require_once 'includes/db.php';

$response = [
    'valid' => true,
    'message' => ''
];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if (empty($name) || empty($email) || empty($phone) || empty($address)) {

    $response['valid'] = false;
    $response['message'] = 'يرجى تعبئة جميع الحقول.';

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $response['valid'] = false;
    $response['message'] = 'يرجى إدخال بريد إلكتروني صحيح.';

} else {

    $sql = "SELECT id FROM students
            WHERE email = :email
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        'email' => $email
    ]);

    if ($stmt->fetch()) {

        $response['valid'] = false;
        $response['message'] = 'هذا البريد الإلكتروني مستخدم بالفعل.';
    }
}

header('Content-Type: application/json');

echo json_encode($response);

?>