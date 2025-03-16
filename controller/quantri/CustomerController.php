<?php
    include dirname(__FILE__).'/../BaseController.php';
    include dirname(__FILE__).'/../../model/Customer.php';
    class CustomerController extends BaseController{
        private $customer;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->customer = new Customer();
        }

        function index(){
            $customers = Customer::getAll();
            //var_dump($customers);
            $result = [
                'paging' => $customers
            ];
            $this->render('Customer', $result, true);
        }

        function getCustomerDetails() {
            if (!isset($_POST['customer_id'])) {
                echo json_encode(['success' => false, 'message' => 'Thiếu customer_id']);
                exit;
            }
            $customerId = (int)$_POST['customer_id'];
            $customer = Customer::findByID($customerId);
            if ($customer !== null) {
                unset($customer['matkhau']);
                $comments = Customer::getCommentsById($customerId);
                $data = $customer;
                $data['comments'] = $comments;
                echo json_encode(['success' => true, 'data' => $data]);
           } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy khách hàng']);
            }
            exit;
        }

    function lockCustomer() {
        if (!isset($_POST['customer_id'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu customer_id']);
            exit;
        }
        $customerId = (int)$_POST['customer_id'];
        $customer = Customer::findByID($customerId);
        $this->customer->nhap($customer['hoten'], $customer['dienthoai'], $customer['email'], $customer['matkhau'], 0, $customer['idQuyen'], $customer['hinhanh'], $customerId);
        if ($customer !== null) {
            $this->customer->lock();
            echo json_encode(['success' => true, 'message' => 'Khóa tài khoản thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy khách hàng']);
        }
        exit;
    }
    
    function unlockCustomer() {
        if (!isset($_POST['customer_id'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu customer_id']);
            exit;
        }
        $customerId = (int)$_POST['customer_id'];
        $customer = Customer::findByID($customerId);
        $this->customer->nhap($customer['hoten'], $customer['dienthoai'], $customer['email'], $customer['matkhau'], 0, $customer['idQuyen'], $customer['hinhanh'], $customerId);
        if ($customer !== null) {
            $this->customer->unlock();
            echo json_encode(['success' => true, 'message' => 'Mở khóa tài khoản thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy khách hàng']);
        }
        exit;
    }

        function search(){
            $pageTitle = 'searchCustomer';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Customer::search($kyw)
            ];
            $this->renderSearch('Customer', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'getCustomerDetails':
                    $this->getCustomerDetails();
                    break;

                case 'lockCustomer':
                    $this->lockCustomer();
                    break;
                
                case 'unlockCustomer':
                    $this->unlockCustomer();
                    break;

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $customerController = new CustomerController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchCustomer') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $customerController->checkAction($action);
?>