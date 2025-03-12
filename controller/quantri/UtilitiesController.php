<?php
    include dirname(__FILE__).'/../BaseController.php';
    include dirname(__FILE__).'/../../model/Utility.php';
    class UtilityController extends BaseController{
        private $utility;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->utility = new Utility();
        }

        function index(){
            $utilities = Utility::getAll();
            $result = [
                'paging' => $utilities
            ];
            $this->render('Utilities', $result, true);
        }

        function add(){
            $this->utility->nhap($_POST['utility_name'], $_POST['utility_icon'], $_POST['utility_status']);
            $req = $this->utility->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false));
            exit;
        }

        function edit(){
            $utility = Utility::findByID($_POST['utility_id']);
            echo json_encode($utility==null ? null: $utility->toArray());
            exit;
        }

        function update(){
            $idTI = $_POST['utility_id'];
            $icon = $_POST['utility_icon'];
            $tenTI = $_POST['utility_name'];
            $trangthai = isset($_POST['status']) ? 1 : 0;
            $this->utility->nhap($tenTI, $icon, $trangthai, $idTI);
            $req = $this->utility->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false));
            exit;
        }

        function search(){
            $pageTitle = 'searchUtility';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Utility::search($kyw)
            ];
            $this->renderSearch('Utility', $result, $pageTitle);
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

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $utilityController = new UtilityController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchUtility') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $utilityController->checkAction($action);
?>