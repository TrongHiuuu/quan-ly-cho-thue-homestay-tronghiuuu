// Owner.js
document.addEventListener('DOMContentLoaded', () => {
    const accountModal = new bootstrap.Modal(document.getElementById('accountModal'));
    const positiveCommentsModal = new bootstrap.Modal(document.getElementById('positiveCommentsModal'));
    const negativeCommentsModal = new bootstrap.Modal(document.getElementById('negativeCommentsModal'));
    let currentAccountId = null;

    // Xử lý khi mở modal thông tin chi tiết (Modal cha)
    $('.open_detail_form').click(function(e) {
        e.preventDefault();
        currentAccountId = $(this).closest('tr').find('.account_id').text();

        $.ajax({
            url: '../controller/quantri/OwnerController.php',
            type: 'POST',
            data: {
                'action': 'getOwnerDetails',
                'owner_id': currentAccountId
            },
            success: function(response){
                const obj = JSON.parse(response);
                if(obj.success == true) {
                    console.log(obj);
                    $('#detail-id').text(obj.data.acc.idTK);
                    $('#detail-name').text(obj.data.acc.hoten);
                    $('#detail-phone').text(obj.data.acc.dienthoai);
                    $('#detail-email').text(obj.data.acc.email);
                    $('#detail-payment').text(obj.data.nganhang);
                    $('#detail-rating').text(obj.data.sosao);
                    $('#detail-status').text(obj.data.acc.trangthai);
                    $('#detail-image').html(obj.data.acc.hinhanh 
                        ? `<img src="${obj.data.acc.hinhanh}" alt="Hình ảnh" style="max-width: 100px;">` 
                        : 'Chưa có');
                }
                accountModal.show();
            }
        });
    });

    // Xử lý khi mở modal bình luận tích cực
    document.getElementById('open-positive-btn').addEventListener('click', () => {
        fetchComments(currentAccountId, true);
        accountModal.hide();
        positiveCommentsModal.show();
    });

    // Xử lý khi mở modal bình luận tiêu cực
    document.getElementById('open-negative-btn').addEventListener('click', () => {
        fetchComments(currentAccountId, false);
        accountModal.hide();
        negativeCommentsModal.show();
    });

    // Khi đóng modal con, hiển thị lại modal cha
    document.getElementById('positiveCommentsModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('positive-comments').innerHTML = '';
        accountModal.show();
    });

    document.getElementById('negativeCommentsModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('negative-comments').innerHTML = '';
        accountModal.show();
    });

    // Hàm lấy và hiển thị bình luận
    function fetchComments(ownerId, isPositive) {
        console.log('Fetching comments - ownerId:', ownerId, 'isPositive:', isPositive);
        $.ajax({
            url: '../controller/quantri/OwnerController.php',
            type: 'POST',
            data: {
                action: 'getComments',
                owner_id: ownerId,
                is_positive: isPositive
            },
            success: function(response) {
                obj = JSON.parse(response);
                console.log('Fetching comments for ID:', ownerId);
                console.log(isPositive ? 'Positive' : 'Negative');
                console.log(obj);
                const container = isPositive 
                    ? document.getElementById('positive-comments') 
                    : document.getElementById('negative-comments');

                if(obj.success && obj.comments.length > 0) {
                    container.innerHTML =obj.comments.map(c => `
                        <div class="comment mb-2 p-2 border rounded">
                            <p><strong>Phòng:</strong> ${c.room_name} | <strong>Sao:</strong> ${c.rating}</p>
                            <p>${c.content}</p>
                        </div>
                    `).join('');
                } else {
                    `<p>Không có bình luận ${isPositive ? 'tích cực' : 'tiêu cực'}.</p>`;
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi khi lấy bình luận:', status, error);
                console.log('Response text:', xhr.responseText); // In dữ liệu thô để debug
                const container = isPositive 
                    ? document.getElementById('positive-comments') 
                    : document.getElementById('negative-comments');
                container.innerHTML = 'Lỗi khi tải dữ liệu.';
            }
        });
    }
});