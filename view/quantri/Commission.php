<main class="container pt-5">
        <!-- 2. -->
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ PHÍ HOA HỒNG</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="row d-flex justify-content-between">
            <div class="col-auto">
                <button class="btn btn-control open_add_form" 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#commissionModal"
                >
                    <i class="fa-regular fa-plus me-2"></i>
                    Thêm phí hoa hồng
                </button>
            </div> 
            <div class="col">
                <form id="search">
                    <input type="hidden" name="page" value="searchCommission">
                    <div class="input-group">
                        <input type="text"
                                class="form-control"
                                placeholder="Nhập id, phần trăm phí hoa hồng"
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
        <!-- ... -->
        <!-- Table data -->
        <div class="row mt-5">
            <div class="col">
                <table class="table table-bordered text-center table-hover align-middle border-success">
                    <thead class="table-header">
                        <tr>
                            <th scope="col">Mã phí hoa hồng</th>
                            <th>Phần trăm hoa hồng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $commisssions = $result['paging'];
                         if($commisssions == null) {
                            echo '<tr><td colspan="4">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $commission = $commisssions[$i];
                        ?>
                        <tr>
                            <td class="commission_id"><?=$commission->getIdMP()?></td>
                            <td class ="commission_rate"><?=$commission->getPhantram()?></td>
                            <td>
                                <button class="btn open-edit-modal fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#commissionModal"
                                >
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete_commission" data-id="<?= $commission->getIdMP() ?>">
                                    <input type="hidden" name="" id="" value="delete_commission">
                                    <i class="fa-solid fa-trash-xmark"></i> 
                                    <!-- Đây là nút xóa phí hoa hồng ra khỏi database, khi xóa set phí hoa hồng của các quận liên quan với phí này về 0 -->
                                </button>
                            </td>
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
        <?php
            }
        ?>
        <!-- ... -->
    </main>
    <!-- ... -->
   <!-- Modal -->
<div class="modal fade" id="commissionModal" tabindex="-1" aria-labelledby="commissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success" id="commissionModalLabel">Thêm phí hoa hồng</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="commissionForm">
                <input type="hidden" name="commission_id" id="commission_id" value=" ">
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="commission_rate" class="col-form-label col-sm-4">Phần trăm hoa hồng</label>
                        <div class="col">
                            <input type="text" name="commission_rate" class="form-control" id="commission_rate">
                            <span class="text-message commission-rate-msg"></span>
                        </div>
                    </div>  
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="col-form-label">Quận/ Huyện/ Thành phố áp dụng:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all-wards">
                                    <label class="form-check-label" for="select-all-wards">Chọn tất cả</label>
                                </div>
                            </div>
                            <input type="text" class="form-control mb-2" id="search-wards" placeholder="Tìm kiếm quận...">
                            <div class="ward-list" style="max-height: 200px; overflow-y: auto;">
                                <?php
                                $wards = Ward::getAll();
                                foreach ($wards as $ward) {
                                    echo '<div class="ward-item form-check">';
                                    echo '<input class="form-check-input ward-checkbox" type="checkbox" name="wards[]" value="' . $ward['idQuan'] . '" id="ward_' . $ward['idQuan'] . '">';
                                    echo '<label class="form-check-label" for="ward_' . $ward['idQuan'] . '">' . $ward['tenQuan'] . '</label>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end w-100"> <!-- Căn phải nút -->
                        <button type="submit" id="saveModalBtn" class="btn btn-success px-4 py-2">Thêm phí hoa hồng</button>
                    </div>
                    <input type="hidden" name="" id="submit_btn">
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- ... -->
    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Commission.js"></script>
</body>
</html>