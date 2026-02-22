<?php
require 'config/db.php';

$stmt = $pdo->query("
    SELECT 
        b.id,
        b.name,
        b.address,
        b.phone,
        b.email,
        IFNULL(AVG(r.rating), 0) AS average_rating
    FROM businesses b
    LEFT JOIN ratings r ON b.id = r.business_id
    GROUP BY b.id
    ORDER BY b.created_at DESC"
);

$businesses = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Business Listing</h1>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            + Add Business
        </button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 border text-left">ID</th>
                    <th class="px-4 py-3 border text-left">Name</th>
                    <th class="px-4 py-3 border text-left">Address</th>
                    <th class="px-4 py-3 border text-left">Phone</th>
                    <th class="px-4 py-3 border text-left">Email</th>
                    <th class="px-4 py-3 border text-center">Avg Rating</th>
                    <th class="px-4 py-3 border text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($businesses) === 0): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No businesses found
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($businesses as $b): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border"><?= $b['id'] ?></td>
                        <td class="px-4 py-2 border font-medium">
                            <?= htmlspecialchars($b['name']) ?>
                        </td>
                        <td class="px-4 py-2 border">
                            <?= htmlspecialchars($b['address']) ?>
                        </td>
                        <td class="px-4 py-2 border">
                            <?= htmlspecialchars($b['phone']) ?>
                        </td>
                        <td class="px-4 py-2 border">
                            <?= htmlspecialchars($b['email']) ?>
                        </td>

                        <td class="px-4 py-2 border text-center">
                            <div class="rating-readonly inline-block" data-score="<?= round($b['average_rating'], 1) ?>">
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= round($b['average_rating'], 1) ?> / 5
                            </div>
                        </td>

                        <td class="px-4 py-2 border text-center">
                            <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Edit
                            </button>
                            <button
                                class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>