<?php
    class Customer{
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

        function nhap(string $hoten, string $dienthoai, string $email, string $matkhau, int $trangthai, int $idQuyen, ?string $hinhanh, int $idTK=0){
            $this->hoten = $hoten;
            $this->dienthoai = $dienthoai;
            $this->email = $email;
            $this->matkhau = $matkhau;
            $this->trangthai = $trangthai;
            $this->idQuyen = $idQuyen;
            $this->hinhanh = $hinhanh;
            $this->idTK = $idTK;
        }

        static function getAll() {
            $list = [];
            $sql = 'SELECT idTK, hoten, email, matkhau, dienthoai, idQuyen, trangthai, hinhanh FROM taikhoan 
            WHERE taikhoan.idQuyen = 3';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){

                $account = new self();
                $account->nhap($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['hinhanh'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                ];
            }
            return $list;
        }

        static function getCommentsById(int $idTK) {
            $sql = 'SELECT dg.binhluan, dg.sosao, dg.ngaytao
                    FROM taikhoan tk
                    LEFT JOIN datphong dp ON tk.idTK = dp.idTK
                    LEFT JOIN danhgia dg ON dp.idDG = dg.idDG
                    WHERE tk.idTK = ' . $idTK;
            $con = new Database();
            $req = $con->getAll($sql);
            return $req;
        }

        static function isExist(int $idTK, string $email){
            $sql = 'SELECT idTK FROM taikhoan WHERE email = "' . $email . '"';
            if($idTK!=0) $sql.=' AND idTK!='.$idTK;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID(int $idTK){
            $sql = 'SELECT idTK, hoten, email, matkhau, dienthoai, idQuyen, trangthai, hinhanh
                    FROM taikhoan
                    WHERE idTK='.$idTK;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $account = new self();
                $account->nhap($req['hoten'], $req['dienthoai'], $req['email'], $req['matkhau'], $req['trangthai'], $req['idQuyen'], $req['hinhanh'], $req['idTK']);
                return $account->toArray();
            }
            return null;
        }

        static function search($kyw){
            $sql = 'SELECT idTK, hoten, email, matkhau, dienthoai, idQuyen, trangthai, hinhanh
                    FROM taikhoan
                    WHERE idQuyen = 3';
            if($kyw != NULL)  $sql .= ' AND (idTK LIKE "%'.$kyw.'%" OR hoten LIKE "%'.$kyw.'%" OR dienthoai LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhap($item['hoten'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idQuyen'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
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
        function updateInfo() {
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

    function updatePassword() {
            $sql = "UPDATE taikhoan SET matkhau = '" .$this->matkhau. "' WHERE idTK = ". $this->idTK;
            $con = new Database();
            $con->execute($sql);
    }

    // Hết phần của Híuuu rồi nè
    
        function setIdTK(int $idTK){
            $this->idTK = $idTK;
        }

        function setHoten(string $hoten){
            $this->hoten = $hoten;
        }

        function setDienthoai(string $dienthoai){
            $this->dienthoai = $dienthoai;
        }

        function setEmail(string $email){
            $this->email = $email;
        }

        function setMatkhau(string $matkhau){
            $this->matkhau = $matkhau;
        }

        function setTrangthai(int $trangthai){
            $this->trangthai = $trangthai;
        }

        function setIdQuyen(int $idQuyen){
            $this->idQuyen = $idQuyen;
        }

        function setHinhanh(string $hinhanh){
            $this->hinhanh = $hinhanh;
        }

        function getIdTK(){
            return $this->idTK;
        }

        function getHoten(){
            return $this->hoten;
        }

        function getDienthoai(){
            return $this->dienthoai;
        }

        function getEmail(){
            return $this->email;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

        function getMatkhau(){
            return $this->matkhau;
        }

        function getIdQuyen(){
            return $this->idQuyen;
        }

        function getHinhanh(){
            return $this->hinhanh;
        }
    }
?>