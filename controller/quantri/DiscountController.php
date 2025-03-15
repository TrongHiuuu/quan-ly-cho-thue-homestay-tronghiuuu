<?php
    include __DIR__.'/../BaseController.php';
    include __DIR__.'/../../model/Discount.php';

    class DiscountController extends BaseController{
        private $discount;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->discount = new Discount();
        }

        function index(){
            $discounts = Discount::getAll();
            $this->render('Discount', array('paging' => $discounts), true);
        }

        function add(){
            $this->discount->nhap($_POST['discount-name'], $_POST['discount-percent'], $_POST['discount-date-start'], $_POST['discount-date-end']);
            $req = $this->discount->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false, 'message'=>'Tên mã giảm giá đã tồn tại'));
            exit;
        }

        function edit(){
            $discount = Discount::findByID($_POST['discount_id']);
            echo json_encode($discount==null ? null: $discount->toArray());
            exit;
        }

        function update(){
            $id = (int)$_POST['discount_id'];
            $tenMGG = $_POST['discount-name'];
            $phantram = floatval($_POST['discount-percent']);
            $ngaybatdau = $_POST['discount-date-start'];
            $trangthai = 'cdr';
            $ngayketthuc = $_POST['discount-date-end'];
            $this->discount->nhap($tenMGG, $phantram, $ngaybatdau, $ngayketthuc, $trangthai, $id);
            $req = $this->discount->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false, 'message'=>'Tên mã giảm giá đã tồn tại'));
            exit;
        }

        function remove(){
            $id = (int)$_POST['discount_id'];
            $discount = Discount::findByID($id);
            
            if(!$discount){
                echo json_encode(array('success'=>false, 'message'=>'Mã giảm giá không tồn tại'));
                exit;
            }
        
            $this->discount->setIdMGG($id);
            if($discount->getTrangthai() === 'cdr'){
                // Xóa hẳn nếu chưa diễn ra
                $this->discount->delete();
                echo json_encode(array('success'=>true, 'message'=>'Đã xóa mã giảm giá'));
            } elseif($discount->getTrangthai() === 'hh'){
                // Ẩn ra khỏi giao diện nếu hết hạn
                $this->discount->setTrangthai('huy');
                $this->discount->hide();
                echo json_encode(array('success'=>true, 'message'=>'Đã xóa mã giảm giá'));
            } else {
                // Không cho xóa nếu đang hoạt động ('hd')
                echo json_encode(array('success'=>false, 'message'=>'Chỉ có thể xóa mã chưa diễn ra hoặc hết hạn'));
            }
            exit;
        }

        function search(){
            $pageTitle = 'searchDiscount';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Discount::search($kyw)
            ];
            $this->renderSearch('Discount', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'submit_btn_add':
                    $this->add();
                    break;
                
                case 'edit_data':
                    $this->edit();
                    break;

                case 'submit_btn_update':
                    $this->update();
                    break;
                
                case 'remove_discount':
                    $this->remove();
                    break;
                
                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $discountController = new DiscountController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchDiscount') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $discountController->checkAction($action);
?>