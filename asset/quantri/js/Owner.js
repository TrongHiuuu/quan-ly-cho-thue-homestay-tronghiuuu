// Owner.js
document.addEventListener('DOMContentLoaded', () => {
    const accountModal = new bootstrap.Modal(document.getElementById('accountModal'));
    const positiveCommentsModal = new bootstrap.Modal(document.getElementById('positiveCommentsModal'));
    const negativeCommentsModal = new bootstrap.Modal(document.getElementById('negativeCommentsModal'));
    const detailButtons = document.querySelectorAll('.open-detail-modal');
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
                // $('#accountModal').modal('show');
                // const obj = response.data;
            }
        });
    });
    // detailButtons.forEach(button => {
    //     button.addEventListener('click', (e) => {
    //         const row = e.target.closest('tr');
    //         const accountId = row.querySelector('.account_id').textContent;
    //         const accountName = row.querySelector('.account_name').textContent;
    //         const accountPhone = row.querySelector('.account_number').textContent;
    //         const accountPayment = row.querySelector('.account_payment_method').textContent;
    //         const accountStatus = row.querySelector('.bg-success') ? 'Hoạt động' : 'Bị khóa';
    //         const accountRating = row.querySelector('.account_rating').textContent;

    //         // Sử dụng AJAX để lấy chi tiết owner
    //         $.ajax({
    //             url: '../controller/quantri/OwnerController.php',
    //             type: 'GET',
    //             data: {
    //                 action: 'getOwnerDetails',
    //                 owner_id: accountId
    //             },
    //             dataType: 'json', // Mong đợi phản hồi là JSON
    //             success: function(data) {
    //                 document.getElementById('detail-id').textContent = accountId;
    //                 document.getElementById('detail-name').textContent = accountName;
    //                 document.getElementById('detail-phone').textContent = accountPhone;
    //                 document.getElementById('detail-payment').textContent = accountPayment;
    //                 document.getElementById('detail-status').textContent = accountStatus;
    //                 document.getElementById('detail-rating').textContent = accountRating;
    //                 document.getElementById('detail-email').textContent = data.email || 'Chưa có';
    //                 document.getElementById('detail-image').innerHTML = data.hinhanh 
    //                     ? `<img src="${data.hinhanh}" alt="Hình ảnh" style="max-width: 100px;">` 
    //                     : 'Chưa có';

    //                 currentAccountId = accountId;
    //                 accountModal.show();
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Lỗi khi lấy chi tiết:', status, error);
    //                 console.log('Response text:', xhr.responseText); // In dữ liệu thô để debug
    //             }
    //         });
    //     });
    // });

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
        accountModal.show();
    });

    document.getElementById('negativeCommentsModal').addEventListener('hidden.bs.modal', () => {
        accountModal.show();
    });

    // Hàm lấy và hiển thị bình luận
    function fetchComments(ownerId, isPositive) {
        $.ajax({
            url: '../controller/quantri/OwnerController.php',
            type: 'POST',
            data: {
                action: 'getComments',
                owner_id: ownerId
            },
            success: function(response) {
                obj = JSON.parse(response);
                if(!obj.success) {
                    const filteredComments = obj.comments.filter(comment => 
                        isPositive ? comment.rating >= 3 : comment.rating < 3
                    );
                    const container = isPositive 
                        ? document.getElementById('positive-comments') 
                        : document.getElementById('negative-comments');
    
                    container.innerHTML = filteredComments.length > 0
                        ? filteredComments.map(c => `
                            <div class="comment mb-2 p-2 border rounded">
                                <p><strong>Phòng:</strong> ${c.room_name} | <strong>Sao:</strong> ${c.rating}</p>
                                <p>${c.content}</p>
                            </div>
                        `).join('')
                        : `<p>Không có bình luận ${isPositive ? 'tích cực' : 'tiêu cực'}.</p>`;
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