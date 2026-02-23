<?php
require '../config/db.php';

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($id <= 0 || $name === '' || $address === '' || $phone === '' || $email === '') {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE businesses
    SET name = ?, address = ?, phone = ?, email = ?
    WHERE id = ?
");

$stmt->execute([$name, $address, $phone, $email, $id]);

echo json_encode([
    'success' => true,
    'data' => [
        'id' => $id,
        'name' => $name,
        'address' => $address,
        'phone' => $phone,
        'email' => $email
    ]
]);