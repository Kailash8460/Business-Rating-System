$(document).ready(function () {
    $('.rating-readonly').each(function () {
        var ratingValue = $(this).data('score');
        $(this).raty({
            readOnly: true,
            half: true,
            score: ratingValue,
            path: 'assets/images/raty'
        });
    });

    $('.rating-readonly').first().raty({
        readOnly: true,
        half: true,
        score: 0,
        path: 'assets/images/raty'
    });

    $('#addBusinessForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'ajax/business_add.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (!res.success) {
                    alert(res.message);
                    return;
                }

                const b = res.data;

                const row = `
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
                        <button class="edit-btn bg-blue-500 text-white px-3 py-1 rounded text-sm">
                            Edit
                        </button>
                        <button class="delete-btn bg-red-500 text-white px-3 py-1 rounded text-sm ml-2">
                            Delete
                        </button>
                    </td>
                </tr>
            `;

                $('tbody').prepend(row);

                $('.rating-readonly').first().raty({
                    readOnly: true,
                    half: true,
                    score: 0
                });

                $('#addBusinessForm')[0].reset();
                // $('#addBusinessModal').modal('hide');
                const modalEl = document.getElementById('addBusinessModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            }
        });
    });
});