<?php
    /*
        Nghiệp vụ của tính năng quản lý phí hoa hồng:
        +  Việc áp dụng một phí hoa hồng
            lên một quận đã có một phí hoa hồng khác từ trước
            sẽ ghi đè mã hoa hồng mới lên quận được chọn
        +  Trong phần chỉnh sửa mức phí hoa hồng
            nếu unset một quận trong danh sách quận được chọn
            thì phí hoa hồng của quận đó sẽ được đặt về null 
    */ 
    include dirname(__FILE__).'/../BaseController.php';
    include dirname(__FILE__).'/../../model/Commission.php';
    include dirname(__FILE__).'/../../model/Ward.php';
    class CommissionController extends BaseController{
        private $commission;
        private $wards;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->commission = new Commission();
        }

        function index(){
            $categories = Commission::getAll();
            $result = [
                'paging' => $categories
            ];
            $this->render('Commission', $result, true);
        }

        function add(){
            // Lấy dữ liệu từ form
            $phantram = floatval($_POST['commission_rate']);
            $wards = $_POST['wards'] ?? []; // Mảng idQuan của các quận được chọn

            // Thêm vào bảng mucphi
            $this->commission->nhap($phantram);
            $req = $this->commission->add();

            if ($req) {
                // Lấy idMP mới nhất sau khi thêm
                $sql = "SELECT MAX(idMP) as idMP from mucphi";
                $con = new Database();
                $lastIdMP = $con->getOne($sql)['idMP'];

                // Cập nhật idMP trong bảng quan cho các quận được chọn
                if (!empty($wards) && is_array($wards)) {
                    foreach ($wards as $idQuan) {
                        // Kiểm tra idQuan hợp lệ
                        $sql = "UPDATE quan 
                        SET
                            idMP = ".$lastIdMP."
                        WHERE idQuan = ".$idQuan." AND (idMP IS NULL OR idMP != ".$lastIdMP.")"
                        ;
                        $con = new Database();
                        $con->execute($sql);
                    }
                }

                echo json_encode(['btn' => 'add', 'success' => true, 'message' => 'Thêm mức phí thành công']);
            } else {
                echo json_encode(['btn' => 'add', 'success' => false, 'message' => 'Mức phí đã tồn tại']);
            }
            exit;
        }

        function edit(){
            $commission = Commission::findByID($_POST['commission_id']);
            if ($commission) {
                $wards = array_column(Ward::getAllByidMP($_POST['commission_id']), 'idQuan');
                // Ward::getAllByidMP($_POST['commission_id']) sẽ trả về 1 list các object
                //  => phải đưa về array chỉ chứa idQuan
                //Sau đó gửi data qua ajax để xử lý
                $data = $commission->toArray();
                $data['wards'] = $wards;
                
                echo json_encode($data);
            } else {
                echo json_encode(null);
            }
            exit;
        }

        function update(){
            $idMP = $_POST['commission_id'];
            $this->commission->nhap($_POST['commission_rate'],  $idMP);
            $req = $this->commission->update();
            if ($req) {
                $con = new Database();
                // Xóa các thành phố cũ
                $sql = "UPDATE quan SET
                idMP = null
                WHERE idMP = ".$idMP;
                $con->execute($sql);
                
                // Thêm các thành phố mới
                $wards = $_POST['wards'] ?? [];
                foreach ($wards as $idQuan) {
                    $sql = "UPDATE quan SET
                    idMP = '.$idMP.'
                    WHERE idQuan = $idQuan";
                    $con->execute($sql);
                }
                echo json_encode(['btn' => 'update', 'success' => true, 'message' => 'Cập nhật mức phí thành công']);
            } else {
                echo json_encode(['btn' => 'update', 'success' => false, 'message' => 'Mức phí đã tồn tại']);
            }
            exit;
        }

        function delete() {
            $idMP = $_POST['commission_id'];
    
            $req = $this->commission->delete($idMP);
    
            if ($req) {
                echo json_encode(['success' => true, 'message' => 'Xóa mức phí thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Xóa mức phí thất bại']);
            }
            exit;
        }

        function search(){
            $pageTitle = 'searchCommission';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Commission::search($kyw)
            ];
            $this->renderSearch('Commission', $result, $pageTitle);
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

                case 'delete_data':
                    $this->delete();
                    break;

                case 'submit_btn_update':
                    $this->update();
                    break;

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $commissionController = new CommissionController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchCommission') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $commissionController->checkAction($action);
?>