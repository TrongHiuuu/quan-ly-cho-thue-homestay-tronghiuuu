<main class="container pt-5">
        <!-- 2. -->
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ TIỆN ÍCH</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="row d-flex justify-content-between">
            <div class="col-auto">
                <button class="btn btn-control open_add_form" 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#utilityModal"
                >
                    <i class="fa-regular fa-plus me-2"></i>
                    Thêm tiện ích
                </button>
            </div> 
            <div class="col">
                <form id="search">
                    <input type="hidden" name="page" value="searchUtility">
                    <div class="input-group">
                        <input type="text"
                                class="form-control"
                                placeholder="Nhập id, tên tiện ích"
                                aria-label="Tìm kiếm tiện ích"
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
                            <th scope="col">Mã tiện ích</th>
                            <th>Icon</th>
                            <th>Tên tiện ích</th>
                            <th>Trạng thái</th>
                            <th>Tùy chỉnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $utilities = $result['paging'];
                         if($utilities == null) {
                            echo '<tr><td colspan="5">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $utility = $utilities[$i];
                        ?>
                        <tr>
                            <td class="utility_id"><?=$utility->getIdTI()?></td>
                            <td class ="utility_icon">
                                <!-- <i src="../asset/uploads/<?=$utility->getIcon()?>"> -->
                                <i class="<?=$utility->getIcon()?>"></i>
                            </td>
                            <td class ="utility_name"><?=$utility->getTenTI()?></td>
                            <td>
                                <?php
                                    if($utility->getTrangthai())
                                        echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                    else
                                        echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                                ?>
                            </td>
                            <td>
                                <button class="btn open-edit-modal fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#utilityModal"
                                >
                                    <i class="fa-regular fa-pen-to-square"></i>
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
    <div class="modal fade" id="utilityModal" tabindex="-1" aria-labelledby="utilityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="utilityModalLabel">Thêm tiện ích</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="utilityForm">
                    <input type="hidden" name="utility_id" id="utility_id" value=" " >
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="category-name" class="col-form-label col-sm-4">Tên tiện ích</label>
                            <div class="col">
                                <input type="text" name="utility_name" class="form-control" id="utility_name">
                                <span class="text-message utility-name-msg"></span>
                            </div>
                        </div>  
                        <div class="row mb-3 align-items-center">
                        <label class="col-form-label col-sm-4">Icon:</label>
                        <div class="col d-flex align-items-center">
                            <div id="selected-icon" class="me-3" style="min-width: 30px; height: 30px; line-height: 30px;">
                                <!-- Icon sẽ được hiển thị ở đây -->
                            </div>
                            <input type="hidden" name="utility_icon" id="utility_icon" value="">
                            <button type="button" class="btn btn-outline-primary" id="openIconPickerModalBtn">
                                Chọn Icon
                            </button>
                        </div>
                        <span class="text-message utility-icon-msg"></span> 
                    </div>
                        <div class="row mb-3 align-items-center edit">  
                            <label class="col-form-label col-sm-4">Trạng thái</label>
                            <div class="col form-check form-switch ps-5">
                                <input  type="checkbox" 
                                        name="status" 
                                        id="status" 
                                        class="form-check-input" 
                                        role="switch" 
                                        value="1"
                                        checked
                                        onchange="document.getElementById('switch-label').textContent = this.checked ? 'Đang hoạt động' : 'Bị khóa';"
                                >
                                <label for="status" class="form-check-label" id="switch-label">Đang hoạt động</label>
                            </div>
                        </div>
                        <input type="hidden" name="status_hidden" value="0">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" id="saveModalBtn" class="btn btn-success">Thêm tiện ích</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal cho phần tìm icon -->
    <div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="iconPickerModalLabel">Chọn Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Tìm kiếm icon...">
                </div>
                <div id="iconList" class="row" style="max-height: 400px; overflow-y: auto;">
                    <!-- Danh sách icon sẽ được hiển thị ở đây -->
                </div>
                <nav aria-label="Icon pagination" class="mt-3">
                    <ul class="pagination justify-content-center" id="iconPagination">
                        <!-- Nút phân trang sẽ được thêm động bằng JS -->
                    </ul>
                </nav>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="selectIconBtn">Chọn Icon</button>
            </div>
        </div>
    </div>
</div>
    <!-- ... -->
    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Utilities.js"></script>
</body>
</html>