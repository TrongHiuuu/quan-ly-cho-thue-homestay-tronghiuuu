// Customer.js
document.addEventListener('DOMContentLoaded', () => {
    const accountModal = new bootstrap.Modal(document.getElementById('accountModal'));
    let currentAccountId = null;

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
                   
                    $('#detail-id').text(obj.data.idTK);
                    $('#detail-name').text(obj.data.hoten);
                    $('#detail-phone').text(obj.data.dienthoai);
                    $('#detail-email').text(obj.data.email);
                    $('#detail-status').text(obj.data.trangthai ? 'Hoạt động' : 'Bị khóa');
                    $('#detail-image').html(obj.data.hinhanh 
                        ? `<img src="${obj.data.hinhanh}" alt="Hình ảnh" style="max-width: 100px;">` 
                        : 'Chưa có');
                }
                accountModal.show();
            }
        });
    });
});