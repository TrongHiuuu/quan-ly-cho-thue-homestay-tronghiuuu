   <!-- Content -->
   <main class="container pt-5">
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ KHÁCH HÀNG</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="col">
                <form id="search">
                    <input type="hidden" name="page" value="searchCustomer">
                    <div class="input-group">
                        <input type="text"
                                class="form-control"
                                placeholder="Nhập id, họ tên, sdti khách hàng"
                                aria-label="Tìm kiếm danh mục"
                                aria-describedby="search-bar"
                                name="kyw"
                                id="search-input"
                        >
                        <button class="btn btn-control" type="submit" id="search-btn">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Table data -->
        <div class="row mt-5">
            <div class="col">
                <table class="table table-bordered text-center table-hover align-middle border-success">
                    <thead class="table-header">
                        <tr>
                            <th scope="col">Mã tài khoản</th>
                            <th>Tên khách hàng</th>
                            <th>Điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                   
                    <tbody>
                    <?php
                        $customers = $result['paging'];
                        if($customers == null) {
                            echo '<tr><td colspan="5">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                            
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $customer = $customers[$i];
                            $acc = $customer['account'];
                        ?>
                            <tr>
                                <td class="account_id"><?=$acc['idTK']?></td>
                                <td class ="account_name"><?=$acc['hoten']?></td>
                                <td class ="account_number"><?=$acc['dienthoai']?></td>
                                <td>
                                    <?php
                                        if($acc['trangthai'])
                                            echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                        else
                                            echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>';
                                    ?>
                                </td>
                                <td>
                                    <button class="btn open-detail-modal fs-5 open_detail_form"
                                                data-bs-toggle="modal"
                                                data-bs-target="#accountModal"
                                                data-account-id="<?=$acc['idTK']?>"
                                    ">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button class="btn toggle-lock-btn" 
                                            data-account-id="<?=$acc['idTK']?>" 
                                            data-status="<?=$acc['trangthai']?>">
                                        <i class="fa-solid <?=$acc['trangthai'] ? 'fa-lock' : 'fa-unlock'?>"></i>
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

    <!-- Modal Thông tin khách hàng -->
    <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="accountModalLabel">Thông tin chi tiết</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="info-section">
                        <p><strong>Mã tài khoản:</strong> <span id="detail-id"></span></p>
                        <p><strong>Họ và tên:</strong> <span id="detail-name"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="detail-phone"></span></p>
                        <p><strong>Email:</strong> <span id="detail-email"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="detail-status"></span></p>
                        <p><strong>Hình ảnh:</strong><span id="detail-image"></span></p>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary view-comments" id="view-comments-btn" data-bs-toggle="modal" data-bs-target="#commentsModal">
                            Xem tất cả bình luận
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal danh sách bình luận -->
    <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentsModalLabel">Bình luận của khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="customer-comments" class="list-group">
                        <!-- Danh sách bình luận -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" id="back-to-account-btn" class="btn btn-secondary back-to-account" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ... -->


    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Customer.js"></script>
</html>

