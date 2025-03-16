// Customer.js
document.addEventListener('DOMContentLoaded', () => {
    const accountModal = new bootstrap.Modal(document.getElementById('accountModal'));
    const commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
    let currentAccountId = null;
    let commentsData = [];

    // // Xử lý tìm kiếm
    // $('#search').submit(function(e) {
    //     e.preventDefault();
    //     const keyword = $('#search-input').val().trim();
    //     if (keyword) {
    //         window.location.href = `?page=search&kyw=${encodeURIComponent(keyword)}`;
    //     } else {
    //         window.location.href = '?page=index'; // Quay lại danh sách gốc nếu không có từ khóa
    //     }
    // });

    // Xử lý khi mở modal thông tin chi tiết 
    $('.open_detail_form').click(function(e) {
        e.preventDefault();
        currentAccountId = $(this).closest('tr').find('.account_id').text();

        $.ajax({
            url: '../controller/quantri/CustomerController.php',
            type: 'POST',
            data: {
                'action': 'getCustomerDetails',
                'customer_id': currentAccountId
            },
            success: function(response){
                const obj = JSON.parse(response);
                console.log(obj);
                console.log(currentAccountId);
                if(obj.success == true) {
                   //Hiển thị thông tin
                    $('#detail-id').text(obj.data.idTK);
                    $('#detail-name').text(obj.data.hoten);
                    $('#detail-phone').text(obj.data.dienthoai);
                    $('#detail-email').text(obj.data.email);
                    $('#detail-status').text(obj.data.trangthai ? 'Hoạt động' : 'Bị khóa');
                    $('#detail-image').html(obj.data.hinhanh 
                        ? `<img src="${obj.data.hinhanh}" alt="Hình ảnh" style="max-width: 100px;">` 
                        : 'Chưa có');

                    commentsData = obj.data.comments || [];
                    accountModal.show();
                }
            }
        });
    });

    $('.view-comments').click(function(e) {
        e.preventDefault();
        const commentsList = $('#customer-comments');
        commentsList.empty();
        if (commentsData.length > 0) {
            commentsData.forEach(comment => {
                const commentItem = `
                    <li class="list-group-item">
                        <strong>Số sao:</strong> ${comment.sosao} <br>
                        <strong>Bình luận:</strong> ${comment.binhluan || 'Không có'} <br>
                        <strong>Ngày tạo:</strong> ${comment.ngaytao || 'Không rõ'}
                    </li>`;
                commentsList.append(commentItem);
            });
        } else {
            commentsList.append('<li class="list-group-item">Không có bình luận nào.</li>');
        }
        commentsModal.show();
    });

    // Xử lý nút "Xem tất cả bình luận" trong modal xem thông tin chi tiết
    // document.getElementById('view-comments-btn').addEventListener('click', () => {
    //     const commentsList = document.getElementById('customer-comments');
    //     commentsList.innerHTML = ''; // Xóa nội dung cũ

    //     if (commentsData.length > 0) {
    //         commentsData.forEach(comment => {
    //             const commentItem = document.createElement('li');
    //             commentItem.className = 'list-group-item';
    //             commentItem.innerHTML = `
    //                 <strong>Số sao:</strong> ${comment.sosao} <br>
    //                 <strong>Bình luận:</strong> ${comment.binhluan || 'Không có'} <br>
    //                 <strong>Ngày tạo:</strong> ${comment.ngaytao || 'Không rõ'}
    //             `;
    //             commentsList.appendChild(commentItem);
    //         });
    //     } else {
    //         commentsList.innerHTML = '<li class="list-group-item">Không có bình luận nào.</li>';
    //     }
    //     //Ẩn modal thông tin tài khoản, hiện modal bình luận
    //     accountModal.hide();
    //     commentsModal.show();
    // });

    // Xử lý nút "Quay lại" trong modal xem bình luận
    $('.back-to-account').click(function(e) {
        e.preventDefault();
        commentsModal.hide();
        accountModal.show();
    });

    // Xử lý nút "Khóa/Mở khóa" trong danh sách tài khoản
    $('.toggle-lock-btn').click(function(e) {
        e.preventDefault();
        const $button = $(this);
        const accountId = $button.data('account-id');
        const currentStatus = $button.data('status'); // 1: Hoạt động, 0: Bị khóa
        const action = currentStatus ? 'lockCustomer' : 'unlockCustomer';

       
        $.ajax({
            url: '../controller/quantri/CustomerController.php',
            type: 'POST',
            data: {
                'action': action,
                'customer_id': accountId
            },
            success: function(response) {
                const obj = JSON.parse(response);
                if (obj.success) {
                    // Lưu trạng thái thành công vào sessionStorage
                    sessionStorage.setItem(action, obj.message || 'Thao tác thành công');
                    // Reload trang
                    location.reload();
                } else {
                    alert(currentStatus ? 'Khóa tài khoản thất bại!' : 'Mở khóa tài khoản thất bại!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi AJAX:', error);
                alert('Có lỗi xảy ra!');
            }
        });
    });

    // Kiểm tra trạng thái sau khi trang reload
    
    if(sessionStorage.getItem('lockCustomer')) { 
        //Nếu trạng thái sau reload là khóa khách hàng, hiển thị thông báo
        //Sau đó loại bỏ trạng thái ra khỏi sessionStorage
        toast({
            title: 'Thành công',
            message: sessionStorage.getItem('lockCustomer'),
            type: 'success',
            duration: 3000
        });
        sessionStorage.removeItem('lockCustomer'); // Loại bỏ trạng thái ra khỏi sessionStorage sau khi đã hiển thị
    }

    if(sessionStorage.getItem('unlockCustomer')) { 
        //Nếu trạng thái sau reload là mở khóa khách hàng, hiển thị thông báo
        //Sau đó loại bỏ trạng thái ra khỏi sessionStorage
        toast({
            title: 'Thành công',
            message: sessionStorage.getItem('unlockCustomer'),
            type: 'success',
            duration: 3000
        });
        sessionStorage.removeItem('unlockCustomer'); // Loại bỏ trạng thái ra khỏi sessionStorage sau khi đã hiển thị
    }
});