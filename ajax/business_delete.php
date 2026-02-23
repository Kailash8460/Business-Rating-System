<?php
require '../config/db.php';

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid business ID'
    ]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM businesses WHERE id = ?");
$stmt->execute([$id]);

echo json_encode([
    'success' => true
]);