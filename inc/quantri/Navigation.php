<?php 
    include "Header.php";
?>
    <!-- Navigation bar -->
    <div class="navbar bg-white shadow-lg p-3">
        <div class="d-flex align-items-center">
            <button class="navbar-toggler" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#sidebar"
                    aria-controls="sidebar"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="index.php" class="navbar-brand ms-3">
                <img src="../asset/quantri/img/vinabook-logo.png" alt="Vinabook" style="width: 250px;">
            </a>
        </div>
        <!-- Sidebar -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebar-label">
            <div class="offcanvas-header">
                <h4 class="offcanvas-title fw-bold fs-3" id="sidebar-label">DANH MỤC QUẢN LÝ</h4>
                <button class="btn-close" data-bs-dismiss="offcanvas" aria-label="close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                        <li class="nav-item sidebar-item permission nhomquyen">
                            <a href="index.php?page=chart" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-chart-simple me-3"></i>
                                Quản lý doanh thu
                            </a>
                        </li>
                        <li class="nav-item sidebar-item">
                            <a href="index.php?page=category" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-house-heart me-3"></i>
                                Quản lý danh mục
                            </a>
                        </li>
                        <li class="nav-item sidebar-item">
                            <a href="index.php?page=utilities" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-list me-3"></i>
                                Quản lý tiện ích
                            </a>
                        </li>
                        <li class="nav-item sidebar-item">
                            <a href="index.php?page=booking" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-receipt me-3"></i>
                                Quản lý đặt phòng
                            </a>
                        </li>
                        <li class="nav-item sidebar-item">
                            <a href="index.php?page=commission" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-money-check-dollar me-3"></i>
                                Quản lý phí hoa hồng
                            </a>
                        </li>
                        <h6 class="nav-item sidebar-item">
                            <a href="#items-manage-user" data-bs-toggle="collapse" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-address-book me-3"></i>
                                Quản lý người dùng
                            </a>
                        </h6>
                        <div class="collapse ps-4" id="items-manage-user">
                            <li class="nav-item sidebar-item">
                                <a href="index.php?page=customer" class="nav-link text-black fs-5 align-items-center">
                                    <i class="fa-regular fa-users me-3"></i>
                                    Quản lý khách hàng
                                </a>
                            </li>
                            <li class="nav-item sidebar-item">
                                <a href="index.php?page=owner" class="nav-link text-black fs-5 align-items-center">
                                    <i class="fa-solid fa-user-tie me-3"></i>    
                                    Quản lý chủ homestay
                                </a>
                            </li>
                        </div>
                        <li class="nav-item sidebar-item">
                            <a href="index.php?page=discount" class="nav-link text-black fs-5 align-items-center">
                                <i class="fa-regular fa-money-check-dollar me-3"></i>
                                Quản lý mã giảm giá
                            </a>
                        </li>
            </ul>
                </div>
            </div>
        <div class="nav justify-content-end d-flex align-items-center">
            <div class="nav-item me-3">
                <a href="?page=info" class="nav-link text-dark">
                    <span class="account-name">
                        <i class="fa-regular fa-user-crown me-2 fs-4"></i>
                        sample
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="btn btn-outline-custom" href="?page=logout">Đăng xuất</a>
            </div>
        </div>
    </div>
    <!-- .... -->