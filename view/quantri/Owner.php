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
                        $owners = $result['paging'];
                        if($owners == null) {
                            echo '<tr><td colspan="6">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                            
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $owner = $owners[$i];
                            $acc = $owner['account'];
                        ?>
                            <tr>
                                <td class="account_id"><?=$acc['idTK']?></td>
                                <td class ="account_name"><?=$acc['hoten']?></td>
                                <td class ="account_payment_method"><?=$owner['nganhang']?></td>
                                <td class ="account_number"><?=$acc['dienthoai']?></td>
                                <td>
                                    <?php
                                        if($acc['trangthai'])
                                            echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                        else
                                            echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>';
                                    ?>
                                </td>
                                <td class ="account_rating"><?php echo floatval($owner['sosao'])?></td>
                                <td>
                                    <button class="btn open-detail-modal fs-5 open_detail_form"
                                                data-bs-toggle="modal"
                                                data-bs-target="#accountModal"
                                                data-account-id="<?=$acc['idTK']?>"
                                    ">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button>
                                            <?php
                                                if($acc['trangthai'])
                                                    echo '<i class="fas fa-lock"></i>';
                                                else
                                                    echo '<i class="fas fa-lock-open"></i>';
                                            ?>
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

<!-- Modal Thông tin chi tiết -->
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
                    <p><strong>Phương thức thanh toán:</strong> <span id="detail-payment"></span></p>
                    <p><strong>Trạng thái:</strong> <span id="detail-status"></span></p>
                    <p><strong>Đánh giá trung bình:</strong> <span id="detail-rating"></span></p>
                    <p><strong>Hình ảnh:</strong><span id="detail-image"></span></p>
                </div>
                <div class="mt-4">
                    <h4>Các bình luận liên quan đến chủ homestay</h4>
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#positiveCommentsModal" id="open-positive-btn">Xem bình luận tích cực</button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#negativeCommentsModal" id="open-negative-btn">Xem bình luận tiêu cực</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bình luận tích cực -->
<div class="modal fade" id="positiveCommentsModal" tabindex="-1" aria-labelledby="positiveCommentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success" id="positiveCommentsModalLabel">Bình luận tích cực (≥ 3 sao)</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="positive-comments">
                <!-- Nội dung bình luận tích cực sẽ được điền bằng JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bình luận tiêu cực -->
<div class="modal fade" id="negativeCommentsModal" tabindex="-1" aria-labelledby="negativeCommentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-danger" id="negativeCommentsModalLabel">Bình luận tiêu cực (< 3 sao)</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="negative-comments">
                <!-- Nội dung bình luận tiêu cực sẽ được điền bằng JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
    <!-- ... -->


    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Owner.js"></script>
</html>

