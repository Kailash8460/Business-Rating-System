<?php
require '../config/db.php';

$businessId = intval($_POST['business_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$rating = floatval($_POST['rating'] ?? 0);

if (
    $businessId <= 0 ||
    $name === '' ||
    $email === '' ||
    $phone === '' ||
    $rating < 0 || $rating > 5
) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id FROM ratings
    WHERE business_id = ?
    AND (email = ? OR phone = ?)
");
$stmt->execute([$businessId, $email, $phone]);

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch();
    $pdo->prepare("
        UPDATE ratings
        SET name = ?, rating = ?, created_at = NOW()
        WHERE id = ?
    ")->execute([$name, $rating, $row['id']]);
} else {
    $pdo->prepare("
        INSERT INTO ratings (business_id, name, email, phone, rating)
        VALUES (?, ?, ?, ?, ?)
    ")->execute([$businessId, $name, $email, $phone, $rating]);
}

$stmt = $pdo->prepare("
    SELECT ROUND(AVG(rating), 1) AS avg_rating
    FROM ratings
    WHERE business_id = ?
");
$stmt->execute([$businessId]);
$avg = $stmt->fetchColumn();

echo json_encode([
    'success' => true,
    'business_id' => $businessId,
    'average_rating' => $avg
]);