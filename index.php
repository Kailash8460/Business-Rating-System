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
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" data-bs-toggle="modal"
            data-bs-target="#addBusinessModal">
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
                            <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                <div class="rating-readonly cursor-pointer inline-flex items-center"
                                    data-business-id="<?= $b['id'] ?>" data-score="<?= round($b['average_rating'], 1) ?>">
                                </div>
                                <span class="text-xs text-gray-500">
                                    <?= round($b['average_rating'], 1) ?> / 5
                                </span>
                            </div>
                        </td>

                        <td class="px-4 py-2 border text-center">
                            <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                                data-id="<?= $b['id'] ?>" data-name="<?= htmlspecialchars($b['name']) ?>"
                                data-address="<?= htmlspecialchars($b['address']) ?>"
                                data-phone="<?= htmlspecialchars($b['phone']) ?>"
                                data-email="<?= htmlspecialchars($b['email']) ?>">
                                Edit
                            </button>
                            <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm ml-2"
                                data-id="<?= $b['id'] ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addBusinessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg">
            <div class="modal-header">
                <h5 class="modal-title font-bold" id="businessModalTitle">Add Business</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="businessForm">
                    <input type="hidden" name="id" id="businessId">

                    <div class="mb-3">
                        <label class="block mb-1">Business Name</label>
                        <input type="text" name="name" id="businessName" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Address</label>
                        <input type="text" name="address" id="businessAddress" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Phone</label>
                        <input type="text" name="phone" id="businessPhone" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Email</label>
                        <input type="email" name="email" id="businessEmail" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <button type="submit" id="businessSubmitBtn"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Save Business
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-lg">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-red-600">
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-gray-700">
                Are you sure you want to delete this business?<br>
                <span class="text-sm text-gray-500">
                    This action cannot be undone.
                </span>
            </div>

            <div class="modal-footer">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
                    data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" id="confirmDeleteBtn"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg">
            <div class="modal-header">
                <h5 class="modal-title font-bold">Rate Business</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="ratingForm">
                    <input type="hidden" id="ratingBusinessId" name="business_id">


                    <div class="mb-3">
                        <label class="block mb-1">Your Name</label>
                        <input type="text" name="name" required class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Email</label>
                        <input type="email" name="email" required class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Phone</label>
                        <input type="text" name="phone" required class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1">Rating</label>
                        <div id="ratingInput" class="flex items-center gap-2"></div>
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Submit Rating
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>