<?php
include dirname(__FILE__).'/Customer.php';
    class Owner extends Customer{
        private string $sodu;
        private ?string $stk;
        private int $idNH;

        function __construct()
        {
            parent::__construct();
            $this->sodu = '';
            $this->stk = '';
            $this->idNH = 0;
        }

    //     public function __call($name, $arguments) {
            
    //         if ($name === 'nhap') {
    //             // Kiểm tra số lượng tham số để phân biệt giữa nhap() của Account và Owner
    //             if (count($arguments) > 8) { // Số tham số của nhap()
    //                 $parentArgs = [
    //                     $arguments[0],  // hoten
    //                     $arguments[1],  // dienthoai
    //                     $arguments[2],  // email
    //                     $arguments[3],  // matkhau
    //                     $arguments[4],  // trangthai
    //                     $arguments[5],  // idQuyen
    //                     $arguments[6],  // hinhanh
    //                     $arguments[7]   // idTK (lấy từ vị trí 7)
    //                 ];
    //                 var_dump($name, $arguments);
    //                 //$parentArgs = array_slice($arguments, 0, 8);

    //                 // callback [$this, 'parent::nhap'] tương đương với việc gọi $this->parent::nhap() 
    //                 // ($this đại diện cho đối tượng của lớp hiện tại - là Owner)
    //                 // Với tham số truyền vào là $parentArrgs gồm 8 phần tử đã được cắt bằng array_slice()
    //                 call_user_func_array([$this, 'parent::nhap'], $parentArgs);

    //                 // // Gán các thuộc tính bổ sung của Owner
    //                 $this->sodu = $arguments[8];  // sodu
    //                 $this->stk = $arguments[9];   // stk
    //                 $this->idNH = $arguments[10]; // idNH
    //             } elseif (count($arguments) <= 8) { // Số tham số của Account::nhap()
    //                 // Chuyển tiếp trực tiếp đến parent::nhap()
    //                 call_user_func_array([$this, 'parent::nhap'], $arguments);
    //             } else {
    //                 throw new Exception("Invalid number of arguments for nhap()");
    //             }
    //         } else {
    //             throw new Exception("Method $name not found in " . __CLASS__);
    //         }
    //     }

    //     // Xử lý các phương thức tĩnh (như getAllOwner(), isExist(), ...)
    // public static function __callStatic($name, $arguments) {
    //     switch ($name) {
    //         case 'getAll':
    //             $list = [];
    //             $sql = 'SELECT hoten, dienthoai, email, matkhau, trangthai, taikhoan.idQuyen, hinhanh, taikhoan.idTK AS idTK, sodu, stk, idNH, dm_quyen.tenQuyen AS tenNQ
    //                     FROM taikhoan 
    //                     LEFT JOIN dm_quyen ON taikhoan.idQuyen = dm_quyen.idQuyen 
    //                     LEFT JOIN host ON taikhoan.idTK = host.idTK
    //                     WHERE taikhoan.idQuyen = 2';
    //             $con = new Database();
    //             $req = $con->getAll($sql);
    //             foreach ($req as $item) {
    //                 $account = new self();
    //                 call_user_func_array([$account, '__call'], ['nhap', $item]);

    //                 // $account->nhap(
    //                 //     $item['hoten'],
    //                 //     $item['dienthoai'],
    //                 //     $item['email'],
    //                 //     $item['matkhau'],
    //                 //     $item['trangthai'],
    //                 //     $item['idQuyen'],
    //                 //     $item['hinhanh'],
    //                 //     $item['idTK'],
    //                 //     $item['sodu'],
    //                 //     $item['stk'],
    //                 //     $item['idNH'],
    //                 // );
    //                 $account->nhap(
    //                     $item['hoten'],
    //                     $item['dienthoai'],
    //                     $item['email'],
    //                     $item['matkhau'],
    //                     $item['trangthai'],
    //                     $item['idQuyen'],
    //                     $item['hinhanh'],
    //                     $item['idTK'],
    //                     $item['sodu'],
    //                     $item['stk'],
    //                     $item['idNH'],
    //                 );
    //                 $list[] = [
    //                     'account' => $account->toArray(),
    //                     'tenNQ' => $item['tenNQ'],
    //                 ];
    //             }
    //             return $list;

        //     case 'isExist':
        //         if (count($arguments) === 2) {
        //             $idTK = $arguments[0];
        //             $stk = $arguments[1];
        //             $sql = 'SELECT taikhoan.idTK AS idTK, hoten, email, matkhau, dienthoai, taikhoan.idQuyen AS idQuyen, dm_quyen.tenQuyen AS tenNQ, taikhoan.trangthai AS trangthai, hinhanh, stk, sodu, idNH
        //                     FROM taikhoan 
        //                     LEFT JOIN host ON taikhoan.idTK = host.idTK
        //                     WHERE stk = "' . $stk . '"';
        //             if ($idTK != 0) $sql .= ' AND idTK != ' . $idTK;
        //             $con = new Database();
        //             return ($con->getOne($sql)) != null;
        //         }
        //         throw new Exception("Invalid arguments for isExist()");

    //         default:
    //             throw new Exception("Static method $name not found in " . __CLASS__);
    //     }
    // }
    
        function nhapOwner(string $hoten, string $dienthoai, string $email, string $matkhau, int $trangthai, $idQuyen, ?string $hinhanh, string $sodu, ?string $stk, int $idNH, int $idTK=0){
            parent::nhap($hoten, $dienthoai, $email, $matkhau, $trangthai, $idQuyen, $hinhanh, $idTK);
            $this->sodu = $sodu;
            $this->stk = $stk;
            $this->idNH = $idNH;
        }
        static function getAll() {
            $list = [];
            $sql ='SELECT t.*, h.sodu, h.stk, h.idNH, AVG(dg.sosao) AS sosao, nh.tenNH
                        FROM taikhoan t
                        LEFT JOIN host h ON t.idTK = h.idTK
                        LEFT JOIN datphong dp ON t.idTK = dp.idTK
                        LEFT JOIN danhgia dg ON dp.idDG = dg.idDG
                        LEFT JOIN nganhang nh ON h.idNH = nh.idNH
                        WHERE t.idQuyen = 2
                        GROUP BY t.idTK';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhapOwner($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['hinhanh'], $item['sodu'], $item['stk'], $item['idNH'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                    'sosao' => $item['sosao'],
                    'nganhang' => $item['tenNH']
                ];
            }
            return $list;
        }

        static function isExist($idTK, $stk){
            $sql = 'SELECT taikhoan.idTK AS idTK
            FROM taikhoan 
            LEFT JOIN host ON taikhoan.idTK = host.idTK
            WHERE stk = "'.$stk.'"';
            if($idTK!=0) $sql.=' AND idTK!='.$idTK;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function searchOwner($kyw){
            $sql = 'SELECT t.*, h.sodu, h.stk, h.idNH, AVG(dg.sosao) AS sosao, nh.tenNH
                        FROM taikhoan t
                        LEFT JOIN host h ON t.idTK = h.idTK
                        LEFT JOIN datphong dp ON t.idTK = dp.idTK
                        LEFT JOIN danhgia dg ON dp.idDG = dg.idDG
                        LEFT JOIN nganhang nh ON h.idNH = nh.idNH
                        WHERE t.idQuyen = 2';
            if($kyw != NULL) $sql .= ' AND (t.idTK LIKE "%'.$kyw.'%" OR t.hoten LIKE "%'.$kyw.'%" OR t.dienthoai LIKE "%'.$kyw.'%")';
            $sql .= ' GROUP BY t.idTK';
            $list = [];
                $con = new Database();
                $req = $con->getAll($sql);
                foreach($req as $item){
                    $host = new Owner();
                    $host->nhapOwner($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['hinhanh'], $item['sodu'], $item['stk'], $item['idNH'], $item['idTK']);
                    $list[] = [
                        'account' => $host->toArray(),
                        'sosao' => $item['sosao'],
                        'nganhang' => $item['tenNH']
                    ];
                }
                return $list;
        }

        static function findByID($idTK){
            $sql ='SELECT t.*, h.sodu, h.stk, h.idNH, AVG(dg.sosao) AS sosao, nh.tenNH
                        FROM taikhoan t
                        LEFT JOIN host h ON t.idTK = h.idTK
                        LEFT JOIN datphong dp ON t.idTK = dp.idTK
                        LEFT JOIN danhgia dg ON dp.idDG = dg.idDG
                        LEFT JOIN nganhang nh ON h.idNH = nh.idNH
                        WHERE t.idQuyen = 2 AND t.idTK = '.$idTK.'
                        GROUP BY t.idTK';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $account = new self();
                $account->nhapOwner($req['hoten'], $req['dienthoai'], $req['email'], $req['matkhau'], $req['trangthai'], $req['idQuyen'], $req['hinhanh'], $req['sodu'], $req['stk'], $req['idNH'], $req['idTK']);
                
                $list[] = [
                    'account' => $account->toArray(),
                    'sosao' => $req['sosao'],
                    'nganhang' => $req['tenNH']
                ];

                return $list;
            }
            return null;
        }

        static function getCommentsByOwner(int $idTK, bool $isPositive) {
            //var_dump($idTK, $isPositive);
            $sql = 'SELECT sanpham.tieude AS room_name, danhgia.sosao AS rating, danhgia.binhluan AS content 
                    FROM sanpham 
                    INNER JOIN datphong ON sanpham.idSP = datphong.idSP 
                    INNER JOIN danhgia ON datphong.idDG = danhgia.idDG 
                    WHERE sanpham.idTK = ' . $idTK . ' AND danhgia.idDG IS NOT NULL';

            if ($isPositive) {
                $sql .= ' AND danhgia.sosao >= 3';
            } else {
                $sql .= ' AND danhgia.sosao < 3';
            }

            $con = new Database();
            $req = $con->getAll($sql);
    
            $comments = [];
            foreach ($req as $item) {
                $comments[] = [
                    'room_name' => $item['room_name'],
                    'rating' => (int)$item['rating'],
                    'content' => $item['content'] ?: 'Không có nội dung'
                ];
            }
            
            return $comments;
        }

        function add(){
            if(!(self::isExist($this->getIdTK(), $this->stk))){
                $con = new Database();
                if(parent::add()) {
                    $sqlHost = 'INSERT INTO host (idTK, sodu, stk, idNH) 
                        VALUES (' . $this->getIdTK() . ', "' . $this->sodu . '", "' . $this->stk . '", ' . $this->idNH . ')';
                    $con->execute($sqlHost);
                    return true;
                }
            }
            return false;
        }
        
        function toArray() {
            return array_merge(parent::toArray(), [
                'sodu' => $this->sodu,
                'stk' => $this->stk,
                'idNH' => $this->idNH,
            ]);
        }

        function setSodu($sodu){
            $this->sodu = $sodu;
        }

        function setStk($stk){
            $this->stk = $stk;
        }

        function setIdNH($idNH){
            $this->idNH = $idNH;
        }

        function getSodu(){
            return $this->sodu;
        }

        function getStk(){
            return $this->stk;
        }

        function getIdNH(){
            return $this->idNH;
        }

    }
?>