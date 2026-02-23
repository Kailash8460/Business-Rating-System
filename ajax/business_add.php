<?php
require '../config/db.php';

$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($name === '' || $address === '' || $phone === '' || $email === '') {
    echo json_encode(['success' => false, 'message' => 'All fields required']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO businesses (name, address, phone, email)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([$name, $address, $phone, $email]);

$id = $pdo->lastInsertId();

echo json_encode([
    'success' => true,
    'data' => [
        'id' => $id,
        'name' => $name,
        'address' => $address,
        'phone' => $phone,
        'email' => $email,
        'average_rating' => 0
    ]
]);