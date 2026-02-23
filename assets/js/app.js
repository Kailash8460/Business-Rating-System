$(document).ready(function () {

    $('.rating-readonly').each(function () {
        $(this).raty({
            readOnly: true,
            half: true,
            score: $(this).data('score'),
            path: 'assets/images/raty'
        });
    });

    $('#businessForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#businessId').val();
        const url = id
            ? 'ajax/business_update.php'
            : 'ajax/business_add.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (!res.success) {
                    alert(res.message || 'Something went wrong');
                    return;
                }

                const b = res.data;

                if (id) {

                    const row = $('button[data-id="' + id + '"]').closest('tr');

                    row.find('td:eq(1)').text(b.name);
                    row.find('td:eq(2)').text(b.address);
                    row.find('td:eq(3)').text(b.phone);
                    row.find('td:eq(4)').text(b.email);

                    const editBtn = row.find('.edit-btn');
                    editBtn.data('name', b.name);
                    editBtn.data('address', b.address);
                    editBtn.data('phone', b.phone);
                    editBtn.data('email', b.email);

                } else {

                    const newRow = `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">${b.id}</td>
                            <td class="px-4 py-2 border font-medium">${b.name}</td>
                            <td class="px-4 py-2 border">${b.address}</td>
                            <td class="px-4 py-2 border">${b.phone}</td>
                            <td class="px-4 py-2 border">${b.email}</td>
                            <td class="px-4 py-2 border text-center">
                                <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                    <div class="rating-readonly" data-score="0"></div>
                                    <span class="text-xs text-gray-500">0 / 5</span>
                                </div>
                            </td>
                            <td class="px-4 py-2 border text-center">
                                <button
                                    class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                                    data-id="${b.id}"
                                    data-name="${b.name}"
                                    data-address="${b.address}"
                                    data-phone="${b.phone}"
                                    data-email="${b.email}">
                                    Edit
                                </button>
                                <button
                                    class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm ml-2">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;

                    $('tbody').prepend(newRow);


                    $('.rating-readonly').first().raty({
                        readOnly: true,
                        half: true,
                        score: 0,
                        path: 'assets/images/raty'
                    });
                }

                $('#businessForm')[0].reset();
                $('#businessId').val('');
                $('#businessModalTitle').text('Add Business');
                $('#businessSubmitBtn').text('Save Business');

                const modalEl = document.getElementById('addBusinessModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.hide();
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {

        $('#businessModalTitle').text('Edit Business');
        $('#businessSubmitBtn').text('Update Business');

        $('#businessId').val($(this).data('id'));
        $('#businessName').val($(this).data('name'));
        $('#businessAddress').val($(this).data('address'));
        $('#businessPhone').val($(this).data('phone'));
        $('#businessEmail').val($(this).data('email'));

        const modalEl = document.getElementById('addBusinessModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });

    let deleteBusinessId = null;
    let deleteRow = null;

    $(document).on('click', '.delete-btn', function () {
        deleteBusinessId = $(this).data('id');
        deleteRow = $(this).closest('tr');

        const modalEl = document.getElementById('deleteConfirmModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });

    $(document).on('click', '#confirmDeleteBtn', function () {

        if (!deleteBusinessId) return;

        $.ajax({
            url: 'ajax/business_delete.php',
            type: 'POST',
            data: { id: deleteBusinessId },
            dataType: 'json',
            success: function (res) {

                if (!res.success) {
                    alert(res.message || 'Delete failed');
                    return;
                }

                deleteRow.fadeOut(300, function () {
                    $(this).remove();
                });

                deleteBusinessId = null;
                deleteRow = null;

                const modalEl = document.getElementById('deleteConfirmModal');
                bootstrap.Modal.getInstance(modalEl).hide();
            }
        });
    });

});