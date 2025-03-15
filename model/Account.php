<?php
    class Account{
        private ?int $idTK;
        private string $hoten;
        private string $dienthoai;
        private string $email;
        private string $matkhau;
        private int $trangthai;
        private int $idQuyen;
        private ?string $hinhanh;
        

        function __construct(){
            $this->idTK=0;
            $this->hoten = '';
            $this->dienthoai = '';
            $this->email = '';
            $this->matkhau = '';
            $this->trangthai = 0;
            $this->idQuyen = 0;
            $this->hinhanh = '';
        }

        function nhap(string $hoten, string $dienthoai, string $email, string $matkhau, int $trangthai, int $idQuyen, ?string $hinhanh, int $idTK = 0){
            $this->hoten = $hoten;
            $this->dienthoai = $dienthoai;
            $this->email = $email;
            $this->matkhau = $matkhau;
            $this->trangthai = $trangthai;
            $this->idQuyen = $idQuyen;
            $this->hinhanh = $hinhanh;
            $this->idTK = $idTK;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT idTK, hoten, email, matkhau, dienthoai, taikhoan.idQuyen AS idQuyen, dm_quyen.ten AS tenNQ, taikhoan.trangthai AS trangthai, hinhanh FROM taikhoan 
            LEFT JOIN dm_quyen ON taikhoan.idQuyen = dm_quyen.idQuyen';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhap($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                    'tenNQ' => $item['tenNQ'],
                ];
            }
            return $list;
        }

        

        static function getCommentsByOwner(int $idTK) {
            $sql = 'SELECT sanpham.tieude AS room_name, danhgia.sosao AS sosao, danhgia.binhluan AS content 
                    FROM sanpham 
                    LEFT JOIN datphong ON sanpham.idSP = datphong.idSP 
                    LEFT JOIN danhgia ON datphong.idDG = danhgia.idDG 
                    WHERE sanpham.idTK = ' . $idTK . ' AND danhgia.idDG IS NOT NULL';
            $con = new Database();
            $req = $con->getAll($sql);
    
            $comments = [];
            foreach ($req as $item) {
                $comments[] = [
                    'room_name' => $item['room_name'],
                    'sosao' => (int)$item['sosao'],
                    'content' => $item['content'] ?: 'Không có nội dung'
                ];
            }
            return $comments;
        }

        static function getAllCustomer() {
            $list = [];
            $sql = 'SELECT taikhoan.idTK AS idTK, hoten, email, matkhau, dienthoai, taikhoan.idQuyen AS idQuyen, dm_quyen.ten AS tenNQ, taikhoan.trangthai AS trangthai, hinhanh, host.stk AS stk FROM taikhoan 
            LEFT JOIN dm_quyen ON taikhoan.idQuyen = dm_quyen.idQuyen
            LEFT JOIN host ON taikhoan.idTK = host.idTK
            WHERE taikhoan.idQuyen = 3';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhap($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                    'tenNQ' => $item['tenNQ']
                ];
            }
            return $list;
        }

        static function isExist(int $idTK, string $email){
            $sql = 'SELECT idTK FROM taikhoan WHERE email = "' . $email . '"';
            if($idTK!=0) $sql.=' AND idTK!='.$idTK;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID(int $idTK){
            $sql = 'SELECT * FROM taikhoan WHERE idTK='.$idTK;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $account = new self();
                $account->nhap($req['hoten'], $req['dienthoai'], $req['email'], $req['matkhau'], $req['trangthai'], $req['idQuyen'], $req['idTK']);
                return $account;
            }
            return null;
        }

        static function findByEmail($email){
            $sql = 'SELECT * FROM taikhoan WHERE email="'.$email.'"';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $account = new self();
                $account->nhap($req['hoten'], $req['dienthoai'], $req['email'], $req['matkhau'], $req['trangthai'], $req['idQuyen'], $req['idTK']);
                return $account;
            }
            return null;
        }

        static function search($kyw, $idQuyen, $trangthai){
            $sql = 'SELECT idTK, hoten, email, matkhau, dienthoai, taikhoan.idQuyen AS idQuyen, dm_quyen.ten AS tenNQ, dm_quyen.trangthai AS trangthaiNQ, taikhoan.trangthai AS trangthai
                FROM taikhoan
                    LEFT JOIN dm_quyen ON taikhoan.idQuyen = dm_quyen.idQuyen
                WHERE 1';
            if($kyw != NULL)  $sql .= ' AND (idTK LIKE "%'.$kyw.'%" OR hoten LIKE "%'.$kyw.'%")';
            if($idQuyen != NULL)  $sql .= ' AND taikhoan.idQuyen = '.$idQuyen;
            if($trangthai != NULL) $sql .= ' AND taikhoan.trangthai = '.$trangthai;
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhap($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                    'tenNQ' => $item['tenNQ']
                ];
            }
            return $list;
        }

        function add(){
            if(!(self::isExist($this->idTK, $this->email))){
                $sql = 'INSERT INTO taikhoan (idTK, hoten, dienthoai, email, matkhau, trangthai, idQuyen, hinhanh) 
                VALUES ("' . $this->idTK .'", "' . $this->hoten . '", "' . $this->dienthoai . '", "' . $this->email . '", "' . $this->matkhau . '", ' . $this->trangthai . ', ' . $this->idQuyen . ', ' . $this->hinhanh . ')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(self::isExist($this->idTK, $this->email))){
                $sql = 'UPDATE taikhoan 
                    SET hoten = "' . $this->hoten . '", 
                        dienthoai = "' . $this->dienthoai . '", 
                        email = "' . $this->email . '",
                        trangthai = ' . $this->trangthai . ', 
                        idQuyen = ' . $this->idQuyen . ' 
                    WHERE idTK = ' . $this->idTK;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function lock(){
            $sql = 'UPDATE taikhoan SET trangthai = 0 WHERE idTK = '.$this->idTK;
            $con = new Database();
            $con->execute($sql);
            return true;
        }

        function unlock() {
            $sql = 'UPDATE taikhoan SET trangthai = 1 WHERE idTK = '.$this->idTK;
            $con = new Database();
            $con->execute($sql);
            return true;
        }
        
        function toArray() {
            return [
                'idTK' => $this->idTK,
                'hoten' => $this->hoten,
                'dienthoai' => $this->dienthoai,
                'email' => $this->email,
                'matkhau' => $this->matkhau,
                'trangthai' => $this->trangthai,
                'idQuyen' => $this->idQuyen,
                'hinhanh' => $this->hinhanh
            ];
        }

        // Của Híuuu - Hàm cập nhật thông tin và hàm cập nhật mật khẩu cho CustomerInfoController.php
        function updateAccountInfo() {
            if(!(self::isExist($this->idTK, $this->email))){
                $fields = [];

                if ($this->hoten != '') {
                    $fields[] = "hoten = '" .$this->hoten. "'";
                }

                if ($this->email != '') {
                    $fields[] = "email = '" .$this->email. "'";
                }

                if ($this->dienthoai != '') {
                    $fields[] = "dienthoai = '" .$this->dienthoai ."'";
                }

                if (empty($fields)) {
                    return true; // Không có gì để cập nhật
                } else {
                    $sql = "UPDATE taikhoan SET " . implode(",", $fields) . " WHERE idTK = ". $this->idTK;
                    $con = new Database();
                        $con->execute($sql);
                        return true;
                }
            }
            return false;
        }

    function updateAccountPassword() {
            $sql = "UPDATE taikhoan SET matkhau = '" .$this->matkhau. "' WHERE idTK = ". $this->idTK;
            $con = new Database();
            $con->execute($sql);
    }

    // Hết phần của Híuuu rồi nè
    
        protected function setIdTK(int $idTK){
            $this->idTK = $idTK;
        }

        protected function setHoten(string $hoten){
            $this->hoten = $hoten;
        }

        protected function setDienthoai(string $dienthoai){
            $this->dienthoai = $dienthoai;
        }

        protected function setEmail(string $email){
            $this->email = $email;
        }

        protected function setMatkhau(string $matkhau){
            $this->matkhau = $matkhau;
        }

        protected function setTrangthai(int $trangthai){
            $this->trangthai = $trangthai;
        }

        protected function setIdQuyen(int $idQuyen){
            $this->idQuyen = $idQuyen;
        }

        protected function setHinhanh(string $hinhanh){
            $this->hinhanh = $hinhanh;
        }

        protected function getIdTK(){
            return $this->idTK;
        }

        protected function getHoten(){
            return $this->hoten;
        }

        protected function getDienthoai(){
            return $this->dienthoai;
        }

        protected function getEmail(){
            return $this->email;
        }

        protected function getTrangthai(){
            return $this->trangthai;
        }

        protected function getMatkhau(){
            return $this->matkhau;
        }

        protected function getIdQuyen(){
            return $this->idQuyen;
        }

        protected function getHinhanh(){
            return $this->hinhanh;
        }
    }
?>