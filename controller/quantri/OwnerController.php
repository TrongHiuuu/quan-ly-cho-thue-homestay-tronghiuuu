<?php
    include dirname(__FILE__).'/../BaseController.php';
    include dirname(__FILE__).'/../../model/Owner.php';
    // include dirname(__FILE__).'/../../model/Host.php';

    class OwnerController extends BaseController{
        private $owner;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->owner= new Owner();
        }

        function index(){
            $owners = Owner::getAll();
            $result = [
                'paging' => $owners,
            ];
            $this->render('Owner', $result, true);
        }

        // function add(){
        //     $matkhau = password_hash($_POST['password'], PASSWORD_DEFAULT);
        //     $this->owner->nhapOwner($_POST['username'], $_POST['userphone'], $_POST['usermail'], $matkhau, 1, $_POST['role-select'], $_POST['user_image']);
        //     $req = $this->owner->add();
        //     if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
        //     else echo json_encode(array('btn'=>'add', 'success'=>false, 'msg'=>'Email đã tồn tại'));
        //     exit;
        // }

        function getComments() {
            if (!isset($_POST['owner_id'])) {
                echo json_encode(['success' => false, 'msg' => 'Thiếu owner_id']);
                exit;
            }
    
            $ownerId = (int)$_GET['owner_id'];
            $comments = Owner::getCommentsByOwner($ownerId);
    
            if ($comments) {
                echo json_encode(['success' => true, 'comments' => $comments]);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Không tìm thấy bình luận']);
            }
            exit;
        }

        function getOwnerDetails() {
            if (!isset($_POST['owner_id'])) {
                echo json_encode(['success' => false, 'msg' => 'Thiếu owner_id']);
                exit;
            }
            $ownerId = (int)$_POST['owner_id'];
            $owner = Owner::findOwnerByID($ownerId);
            if ($owner) {
                $data = [
                    'acc' => $owner[0]['account'],
                    'sosao' => $owner[0]['sosao'],
                    'nganhang' => $owner[0]['nganhang']
                    ];
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Không tìm thấy chủ homestay']);
            }
            exit;
        }

        function search() {
            $pageTitle = 'searchOwner';
            $kyw = NULL;
    
            if (isset($_GET['kyw']) && ($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
    
            $result = [
                'paging' => Owner::searchOwner($kyw),
            ];
            $this->renderSearch('Owner', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;
                
                case 'search':
                    $this->search();
                    break;

                case 'getComments':
                    $this->getComments();
                    break;

                case 'getOwnerDetails':
                    $this->getOwnerDetails();
                    break;

            }
        }
    }

    $ownerController = new OwnerController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchOwner') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $ownerController->checkAction($action);
?>