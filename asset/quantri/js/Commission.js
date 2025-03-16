// 3
// Reset
document.getElementById('commissionModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('commissionForm').reset();
    let textMessage = document.querySelectorAll('.text-message');
    textMessage.forEach(element => {
        element.textContent = '';
    });
    location.reload();
});

$(document).ready(function() {
    const modalTitle = document.getElementById('commissionModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    // open add form
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm phí hoa hồng';
        modalSaveBtn.textContent = 'Thêm phí hoa hồng';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        //document.getElementById('commissionForm').querySelector('.edit').style.display = 'none';
    });
   // open edit form
   $('.open_edit_form').click(function(e) {
        e.preventDefault();
        // this = open_edit_form
        // .closest('tr') tìm kiếm phần tử cha gần nhất có thẻ <tr> (table row) từ phần tử hiện tại (this)        
        // .find('.commission_id') sẽ tìm kiếm phần tử con bên trong hàng đó có class author_id.
        // .text() sẽ lấy nội dung văn bản của phần tử được tìm thấy
        var commission_id = $(this).closest('tr').find('.commission_id').text();
        modalTitle.textContent = 'Chỉnh sửa phí hoa hồng';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        $.ajax({
            url: '../controller/quantri/CommissionController.php',
            type: 'POST',
            data: {
                'action': 'edit_data',
                'commission_id': commission_id,
            },
            success: function(response){
                const obj = JSON.parse(response);
                console.log(obj);
                $('#commission_id').val(obj.idMP);
                $('#commission_rate').val(obj.phantram);
                $('.ward-checkbox').prop('checked', false);
                if (obj.wards && Array.isArray(obj.wards)) {
                    obj.wards.forEach(function(wardId) {
                        const checkbox = $('#ward_' + wardId);
                        if (checkbox.length) { // Kiểm tra checkbox tồn tại
                            checkbox.prop('checked', true);
                        }
                        // else {
                        //     console.warn('Checkbox không tồn tại cho wardId:', wardId);
                        // }
                    });
                }
                $('#search-wards').val('');
                $('.ward-list .form-check').show();
                //document.getElementById('commissionForm').querySelector('.edit').style.display = 'flex';
            },
        });
    });

    // Xử lý nút "Chọn tất cả" - Kể cả các thành phố k được hiển thị
    // $('#select-all-wards').click(function() {
    //     var isAllChecked = $('.ward-checkbox:not(:checked)').length === 0; // Kiểm tra xem tất cả đã được chọn chưa
    //     $('.ward-checkbox').prop('checked', !isAllChecked); // Nếu tất cả đã chọn thì bỏ chọn, ngược lại chọn tất cả
    // });

    // Xử lý nút "Chọn tất cả" - Chỉ bao gồm các thành phố đươc hiển thị
    $('#select-all-wards').click(function() {
        var isAllChecked = $('.ward-checkbox:not(:checked)').length === 0;
        $('.ward-checkbox:visible').prop('checked', !isAllChecked); // Chỉ chọn các checkbox đang hiển thị
    });

    // Xử lý tìm kiếm quận
    $('#search-wards').on('input', function() {
        var searchTerm = $(this).val().trim().toLowerCase(); // Lấy từ khóa và chuẩn hóa
        $('.ward-list .form-check').each(function() {
            var wardName = $(this).find('label').text().toLowerCase(); // Lấy tên quận
            if (wardName.includes(searchTerm)) {
                $(this).show(); // Hiển thị nếu khớp
            } else {
                $(this).hide(); // Ẩn nếu không khớp
            }
        });
    });

    // Xử lý submit form
    $('#commissionForm').submit(function(event) {
        event.preventDefault();
        var phantram = $('#commission_rate').val().trim();
        if (phantram != "") {
            
            var formData = new FormData($('#commissionForm')[0]);
            console.log(formData);
            $.ajax({
                url: '../controller/quantri/CommissionController.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if (obj.success) {
                        if (obj.btn == 'add') {
                            toast({
                                title: 'Thành công',
                                message: obj.message,
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: obj.message,
                                type: 'success',
                                duration: 3000
                            });
                        }
                        //$('#commissionModal').modal('hide');
                    } else {
                        if (obj.btn == 'add') {
                            $('.text-message.commission-rate-msg').text('Phí hoa hồng đã tồn tại');
                        } else {
                            toast({
                                title: 'Lỗi',
                                message: obj.message,
                                type: 'error',
                                duration: 3000
                            });
                        }
                    }
                },
            });
        } else {
            $('.text-message.commission-rate-msg').text('Phần trăm hoa hồng không được để trống');
        }
    });

    // Reset khi modal đóng
    document.getElementById('commissionModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('commissionForm').reset();
        $('.ward-checkbox').prop('checked', false);
        $('#search-wards').val(''); // Reset ô tìm kiếm
        $('.ward-list .form-check').show(); // Hiển thị lại tất cả quận
        let textMessage = document.querySelectorAll('.text-message');
        textMessage.forEach(element => {
            element.textContent = '';
        });
        location.reload();
    });

    // Xử lý xóa mức phí
    $(document).on('click', '.delete_commission', function() {
        var commission_id = $(this).data('id');
        console.log(commission_id);
        var row = $(this).closest('tr');

        // Hiển thị xác nhận trước khi xóa
        if (confirm('Bạn có chắc chắn muốn xóa mức phí này không?')) {
            $.ajax({
                url: '../controller/quantri/CommissionController.php',
                type: 'POST',
                data: {
                    'action': 'delete_data',
                    'commission_id': commission_id,
                },
                success: function(response) {
                    const obj = JSON.parse(response);
                    if (obj.success) {
                        toast({
                            title: 'Thành công',
                            message: obj.message,
                            type: 'success',
                            duration: 3000
                        });
                        //row.remove();
                        location.reload() // Xóa dòng khỏi bảng
                    } else {
                        toast({
                            title: 'Lỗi',
                            message: obj.message,
                            type: 'error',
                            duration: 3000
                        });
                    }
                },
                error: function() {
                    toast({
                        title: 'Lỗi',
                        message: 'Có lỗi xảy ra khi xóa mức phí',
                        type: 'error',
                        duration: 3000
                    });
                }
            });
        }
    });

});