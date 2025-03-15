// 3
// Reset
document.getElementById('openIconPickerModalBtn').addEventListener('click', function (e) {
    const iconPickerModal = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    document.getElementById('utilityModal').style.opacity = "0.5"; 
    iconPickerModal.show(); // Hiển thị modal chọn icon
});

document.getElementById('iconPickerModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('utilityModal').style.opacity = "1"; 
});

document.getElementById('utilityModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('utilityForm').reset();
        let textMessage = document.querySelectorAll('.text-message');
        textMessage.forEach(element => {
            element.textContent = '';
        });
        location.reload();
});

$(document).ready(function() {
    const modalTitle = document.getElementById('utilityModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    // open add form
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm danh mục';
        modalSaveBtn.textContent = 'Thêm danh mục';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        document.getElementById('utilityForm').querySelector('.edit').style.display = 'none';
    });
   // open edit form
   $('.open_edit_form').click(function(e) {
        e.preventDefault();
        // this = open_edit_form
        // .closest('tr') tìm kiếm phần tử cha gần nhất có thẻ <tr> (table row) từ phần tử hiện tại (this)        
        // .find('.utility_id') sẽ tìm kiếm phần tử con bên trong hàng đó có class author_id.
        // .text() sẽ lấy nội dung văn bản của phần tử được tìm thấy
        var utility_id = $(this).closest('tr').find('.utility_id').text();
        modalTitle.textContent = 'Chỉnh sửa danh mục';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        $.ajax({
            url: '../controller/quantri/UtilitiesController.php',
            type: 'POST',
            data: {
                'action': 'edit_data',
                'utility_id': utility_id,
            },
            success: function(response){
                const obj = JSON.parse(response);
                if (obj.success) {
                    console.log(obj.data);
                    $('#utility_id').val(obj.data.idTI);
                    $('#utility_name').val(obj.data.tenTI);
                    $('#utility_icon').val(obj.data.icon || '');
                    /*
                        Chỗ này cố tình sử dụng cặp `` thay vì ' ', bởi nếu sử dụng cặp
                        dấu nháy đơn thông thường thì ${obj.icon || ''} sẽ được hiểu cũng là một string
                        Nếu sử dụng `` thì ta có thể thay data của biến vào chuỗi như bình thường
                        (chèn biến động)
                    */
                    $('#selected-icon').html(`<i class="${obj.data.icon || ''} fs-3"></i>`);
                    if(parseInt(obj.data.trangthai)){
                        $('#status').prop('checked', true);
                        $('#switch-label').text('Đang hoạt động');
                    }
                    else {
                        $('#status').prop('checked', false);
                        $('#switch-label').text('Bị khóa');
                    }
                    document.getElementById('utilityForm').querySelector('.edit').style.display = 'flex';
                }
            },
        });
    });

    $('#utilityForm').submit(function(event) {
        event.preventDefault();
        var ten = $('#utility_name').val().trim();
        var selectedIcon = $('#selected-icon').val().trim();
        var icon = $('#utility_icon').val().trim();
        if(ten != "" && icon != "") {
            var formData = new FormData( $('#utilityForm')[0]);
            var utility_status = document.getElementById('status').checked ? '1' : '0';
            formData.set('utility_status', utility_status)
            //processData: false Không tự động chuyển đổi dữ liệu thành chuỗi query 
            //contentType: false Không đặt kiểu nội dung, điều này cho phép jQuery tự động thiết lập nội dung cho yêu cầu
            $.ajax({
                url: '../controller/quantri/UtilitiesController.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // In ra phản hồi từ server 
                    console.log(response);
                    const obj = JSON.parse(response);
                    // Chuyển đổi chuỗi JSON từ server thành đối tượng JavaScript.
                    if(obj.success){
                        if(obj.btn == 'add') {
                            toast({
                                title: 'Thành công',
                                message: 'Thêm danh mục thành công',
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: 'Cập nhật danh mục thành công',
                                type: 'success',
                                duration: 3000
                            });
                        }
                    }
                    else {  
                        if (obj.btn == 'add') {
                            $('.text-message.utility-name-msg').text('Danh mục đã tồn tại');
                        } else {
                            toast({
                                title: 'Lỗi',
                                message: 'Cập nhật danh mục thất bại',
                                type: 'error',
                                duration: 3000
                            });
                        }
                    }
                },
            });
        }
        else {
            if (ten == "") {    
                $('.text-message.utility-name-msg').text('Tên danh mục không được để trống');
            }
            if (icon == "" && selectedIcon == "") {
                $('.text-message.utility-icon-msg').text('Vui lòng chọn một icon');
            }
        }
    });
});

// ===== Xử lý iconPickerModal =====

let iconsData = null; // Biến toàn cục để lưu dữ liệu JSON
let filteredIcons = null; // Dữ liệu đã lọc khi tìm kiếm
let selectedIcon = null; // Icon được chọn
const iconsPerPage = 18; // Số icon mỗi trang
let currentPage = 1; // Trang hiện tại
let isFirstLoad = true; // Kiểm tra lần đầu mở modal

/// Hàm tải dữ liệu từ icon.json
function loadIcons() {
    return fetch('../asset/quantri/json/icons.json') // Điều chỉnh đường dẫn theo cấu trúc dự án
        .then(response => {
            if (!response.ok) throw new Error('Không thể tải file icons.json');
            return response.json();
        })
        .then(data => {
            iconsData = data;
            //Lọc danh sách icon, chỉ lấy solid hoặc brands
            filteredIcons = Object.keys(data).reduce((acc, key) => {
                if (data[key].styles.includes('solid') || data[key].styles.includes('brands')) {
                    acc[key] = data[key];
                }
                return acc;
            }, {});
        })
        .catch(error => {
            console.error('Lỗi khi tải icons:', error);
            toast({ title: 'Lỗi', message: 'Không thể tải danh sách icon', type: 'error', duration: 3000 });
        });
}

// Hàm hiển thị danh sách icon trong #iconPickerModal
function displayIcons(page = 1, data = filteredIcons) {
    const iconList = document.getElementById('iconList');
    iconList.innerHTML = ''; // Xóa nội dung cũ

    const iconKeys = Object.keys(data);
    const totalIcons = iconKeys.length;
    const totalPages = Math.ceil(totalIcons / iconsPerPage);
    currentPage = Math.min(Math.max(page, 1), totalPages); // Giới hạn trang từ 1 đến totalPages

    const start = (currentPage - 1) * iconsPerPage;
    const end = Math.min(start + iconsPerPage, totalIcons);

    for (let i = start; i < end; i++) {
        const key = iconKeys[i];
        const icon = data[key];
        const iconClass = `${icon.styles[0] === 'solid' ? 'fas' : 'fab'} fa-${key}`;
        const iconItem = document.createElement('div');
        iconItem.className = 'icon-item col-2 text-center p-2';
        iconItem.innerHTML = `
            <i class="${iconClass} fs-3"></i>
            <p class="small mt-1">${key}</p>
        `;
        iconItem.dataset.icon = key;
        iconItem.addEventListener('click', function() {
            document.querySelectorAll('.icon-item').forEach(item => item.classList.remove('selected'));
            this.classList.add('selected');
            selectedIcon = key;
        });
        iconList.appendChild(iconItem);
    }

    // Hiển thị phân trang
    displayPagination(totalPages);
}

// Tìm kiếm icon
document.getElementById('iconSearch').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase().trim();  //Chuẩn hóa dữ liệu nhập vào (target ở đây là iconSearch - phần tử HTML đã kích hoạt sự kiện)
    if (!iconsData) return;

    if (query === '') {
        // Nếu thanh tìm kiếm trống, hiển thị toàn bộ danh sách ban đầu
        filteredIcons = Object.keys(iconsData).reduce((acc, key) => {
            if (iconsData[key].styles.includes('solid') || iconsData[key].styles.includes('brands')) {
                acc[key] = iconsData[key];
            }
            return acc;
        }, {});
    } else {
        // Lọc icon dựa trên query
        filteredIcons = Object.keys(iconsData).reduce((acc, key) => {
            const icon = iconsData[key];
            if ((icon.styles.includes('solid') || icon.styles.includes('brands')) &&
                (key.toLowerCase().includes(query) ||  icon.search.terms.some(term => term.toLowerCase().includes(query)))) {
                acc[key] = icon;
            }
            /*
                .some(): phương thức của mảng, trả về true nếu 
                ít nhất một phần tử trong mảng thỏa mãn điều kiện được định nghĩa trong hàm callback,
                và false nếu không có phần tử nào thỏa mãn.
            */
            return acc;
        }, {});
    }
    
    currentPage = 1; // Reset về trang 1 khi tìm kiếm
    displayIcons(currentPage, filteredIcons); // Hiển thị kết quả tìm kiếm
});

/* 
    Hàm hiển thị phân trang với số nút giới hạn
    Nút "Trước".
    Trang đầu (nếu không gần trang hiện tại).
    Dấu ba chấm (...) nếu có trang bị ẩn.
    Một số trang gần trang hiện tại.
    Dấu ba chấm (...) nếu còn trang sau.
    Trang cuối (nếu không gần trang hiện tại).
    Nút "Sau".
*/
function displayPagination(totalPages) {
    const pagination = document.getElementById('iconPagination');
    pagination.innerHTML = '';
    const maxVisiblePages = 8; // Số nút trang tối đa hiển thị

    // Tính toán các trang hiển thị
    let startPage, endPage;
    if (totalPages <= maxVisiblePages) { // Nếu số nút trang tối đa hiển thị lớn hơn hoặc bằng tổng số trang, hiển thị hết
        startPage = 1;
        endPage = totalPages; 
    } else {
        const halfVisible = Math.floor(maxVisiblePages / 2); //Số lượng trang hiển thị ở mỗi bên trái và bên phải
        startPage = Math.max(1, currentPage - halfVisible); //Tính trang bắt đầu của phạm vi hiển thị, không nhỏ hơn 1
        endPage = Math.min(totalPages, currentPage + halfVisible); //Tính trang cuối cùng của phạm vi hiển thị, không lớn hơn totalPages

        // Điều chỉnh nếu gần đầu hoặc cuối danh sách
        if (currentPage <= halfVisible + 1) {
            endPage = maxVisiblePages;
        } else if (currentPage + halfVisible >= totalPages) {
            startPage = totalPages - maxVisiblePages + 1;
        }
    }

    // Nút Previous
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#">Trước</a>`;
    prevLi.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) displayIcons(currentPage - 1);
    });
    pagination.appendChild(prevLi);

    // Trang đầu (nếu không nằm trong phạm vi hiển thị)
    if (startPage > 1) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="#">1</a>`;
        firstLi.addEventListener('click', (e) => {
            e.preventDefault();
            displayIcons(1);
        });
        pagination.appendChild(firstLi);

        if (startPage > 2) {
            const ellipsisLi = document.createElement('li');
            ellipsisLi.className = 'page-item disabled';
            ellipsisLi.innerHTML = `<span class="page-link">...</span>`;
            pagination.appendChild(ellipsisLi);
        }
    }

    // Các trang trong phạm vi
    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        li.addEventListener('click', (e) => {
            e.preventDefault();
            displayIcons(i);
        });
        pagination.appendChild(li);
    }

    // Trang cuối (nếu không nằm trong phạm vi hiển thị)
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const ellipsisLi = document.createElement('li');
            ellipsisLi.className = 'page-item disabled';
            ellipsisLi.innerHTML = `<span class="page-link">...</span>`;
            pagination.appendChild(ellipsisLi);
        }

        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="#">${totalPages}</a>`;
        lastLi.addEventListener('click', (e) => {
            e.preventDefault();
            displayIcons(totalPages);
        });
        pagination.appendChild(lastLi);
    }

    // Nút Next
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#">Sau</a>`;
    nextLi.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) displayIcons(currentPage + 1);
    });
    pagination.appendChild(nextLi);
}

//Xử lý mở modal Icon Picker
document.getElementById('openIconPickerModalBtn').addEventListener('click', async function() {
    const iconPickerModal = new bootstrap.Modal(document.getElementById('iconPickerModal'));
    document.getElementById('utilityModal').style.opacity = "0.5";
    
    if (isFirstLoad) {
        await loadIcons(); // Chỉ load dữ liệu lần đầu
        isFirstLoad = false;
    }
    displayIcons(currentPage); // Hiển thị trang hiện tại
    iconPickerModal.show();
});

// Xử lý chọn icon: xác nhận và cập nhật vào modal cha
document.getElementById('selectIconBtn').addEventListener('click', function() {
    if (selectedIcon) {
        const selectedIconDiv = document.getElementById('selected-icon');
        const iconClass = `${iconsData[selectedIcon].styles[0] === 'solid' ? 'fas' : 'fab'} fa-${selectedIcon}`;
        selectedIconDiv.innerHTML = `<i class="${iconClass} fs-3"></i>`;
        document.getElementById('utility_icon').value = iconClass; // Lưu class đầy đủ
        bootstrap.Modal.getInstance(document.getElementById('iconPickerModal')).hide();
    }
});
