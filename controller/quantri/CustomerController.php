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
                echo json_encode(['success' => false, 'msg' => 'Thiếu customer_id']);
                exit;
            }
            $customerId = (int)$_POST['customer_id'];
            $customer = Customer::findByID($customerId);
            unset($customer['matkhau']);
            if ($customer !== null) {
                echo json_encode(['success' => true, 'data' => $customer]);
           } else {
                echo json_encode(['success' => false, 'msg' => 'Không tìm thấy khách hàng']);
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