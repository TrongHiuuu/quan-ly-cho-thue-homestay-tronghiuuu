   <!-- Content -->
   <main class="container pt-5">
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ CHỦ HOMESTAY</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <!-- ... -->
        <!-- Table data -->
        <div class="row mt-5">
            <div class="col">
                <table class="table table-bordered text-center table-hover align-middle border-success">
                    <thead class="table-header">
                        <tr>
                            <th scope="col">Mã tài khoản</th>
                            <th>Tên chủ homestay</th>
                            <th>Phương thức thanh toán</th>
                            <th>Điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Đánh giá</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                   
                    <tbody>
                    <?php
                        $accounts = $result['paging'];
                        if($accounts == null) {
                            echo '<tr><td colspan="6">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                            
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $account = $accounts[$i];
                            $acc = $account['account'];
                        ?>
                            <tr>
                                <td class="account_id"><?=$acc['idTK']?></td>
                                <td class ="account_name"><?=$acc['tenTK']?></td>
                                <td class ="account_email"><?=$acc['email']?></td>
                                <td class ="account_number"><?=$acc['dienthoai']?></td>
                                <td class ="account_role"><?=$account['tenNQ']?></td>
                                <td>
                                    <?php
                                        if($acc['trangthai'])
                                            echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                        else
                                            echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                                    ?>
                                </td>
                                <td>
                                    <button class="btn open-edit-modal fs-5 open_edit_form action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#accountModal"
                                    >
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button>
                                        <i class="fas fa-lock"></i> 
                                        <i class="fas fa-lock-open"></i>
                                        <!-- Đây là nút khóa/ mở khóa-->
                                    </button>
                                </td>
                                <?php
                                    }
                                ?>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- ... -->
        <!-- Pagination -->
        <div class="row mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                <?php
                    echo $pagingButton;
                ?>
                </ul>
              </nav>
        </div>
        <!-- ... -->
    </main>
    <!-- ... -->


    <!-- Modal -->
    <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="accountModalLabel">Thêm tài khoản</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="accountForm">
                    <input type="hidden" name="account_id" id="account_id" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Họ và tên</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Nhập họ và tên">
                            <span class="text-message user-name-msg"></span>
                        </div>
                        <div class="mb-3">
                            <label for="usermail" class="form-label">Email</label>
                            <input type="email" name="usermail" id="usermail" class="form-control" placeholder="Nhập địa chỉ email">
                            <span class="text-message user-email-msg"></span>
                        </div>
                        <div class="mb-3 add">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu">
                            <span class="text-message user-password-msg"></span>
                        </div>
                        <div class="mb-3">
                            <label for="userphone" class="form-label">Số điện thoại</label>
                            <input type="tel" name="userphone" id="userphone" class="form-control" placeholder="Nhập số điện thoại">
                            <span class="text-message user-phone-msg"></span>
                        </div>
                        <div class="row mb-3">
                            <label for="userrole" class="col-form-label col-sm-3">Nhóm quyền</label>
                            <div class="col">
                                <select name="role-select" id="role-select" class="form-select">      
                                </select>
                                <span class="text-message user-select-msg"></span>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center edit">
                            <label class="col-form-label col-sm-3">Trạng thái</label>
                            <div class="col form-check form-switch ps-5">
                                <input  type="checkbox"
                                        name="status"
                                        id="status"
                                        class="form-check-input"
                                        role="switch"
                                        onchange="document.getElementById('switch-label').textContent = this.checked ? 'Đang hoạt động' : 'Bị khóa';"
                                >
                                <label for="status" class="form-check-label" id="switch-label">Đang hoạt động</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" class="btn btn-success" id="saveModalBtn" >Thêm tài khoản</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ... -->


    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Account.js"></script>
</html>

