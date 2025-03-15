<?php
    class Discount{
        private int $idMGG;
        private string $tenMGG;
        private float $phantram;
        private string $ngaybatdau;
        private string $ngayketthuc;
        private string $trangthai;

        function nhap(string $tenMGG, float $phantram, string $ngaybatdau, string $ngayketthuc, string $trangthai = 'cdr', int $idMGG=0){
            $this->tenMGG = $tenMGG;
            $this->idMGG = $idMGG;
            $this->phantram = $phantram;
            $this->ngaybatdau = $ngaybatdau;
            $this->ngayketthuc = $ngayketthuc;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            //Chỉ lấy những MGG chưa hủy
            $list = [];
            $sql = 'SELECT * FROM magiamgia WHERE trangthai != "huy"';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $discount = new Discount();
                $discount->nhap($item['tenMGG'], $item['phantram'], $item['ngaybatdau'], $item['ngayketthuc'], $item['trangthai'], $item['idMGG']);
                $list[] = $discount;
            }
            return $list;
        }

        static function getAllWaiting(){
            $list = [];
            $sql = 'SELECT * FROM magiamgia WHERE trangthai = "cdr"';
            $con = new Database();
            $req = $con->getAll($sql);
        
            foreach($req as $item){
                $discount = new Discount();
                $discount->nhap($item['tenMGG'], $item['phantram'], $item['ngaybatdau'], $item['ngayketthuc'], $item['trangthai'], $item['idMGG']);
                $list[] = $discount;
            }
            return $list;
        }

        static function isExist(int $idMGG, string $tenMGG){
            $sql = 'SELECT idMGG FROM magiamgia WHERE tenMGG= "'.$tenMGG.'"';
            if($idMGG!=0) $sql.=' AND idMGG!='.$idMGG;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idMGG){
            $sql = 'SELECT * FROM magiamgia WHERE idMGG='.$idMGG;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $discount = new Discount();
                $discount->nhap($req['tenMGG'], $req['phantram'], $req['ngaybatdau'], $req['ngayketthuc'], $req['trangthai'], $req['idMGG']);
                return $discount;
            }
            return null;
        }

        static function search($kyw){
            $sql = 'SELECT DISTINCT idMGG, tenMGG, phantram, ngaybatdau, ngayketthuc, trangthai
                    FROM magiamgia
                    WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idMGG LIKE "%'.$kyw.'%" OR tenMGG LIKE "%'.$kyw.'%" OR phantram LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $discount = new Discount();
                $discount->nhap($item['tenMGG'], $item['phantram'], $item['ngaybatdau'], $item['ngayketthuc'], $item['trangthai'], $item['idMGG']);
                $list[] = $discount;
            }
            return $list;
        }

        function add(){
            if(!(Discount::isExist($this->idMGG, $this->tenMGG))){
                $sql='INSERT INTO magiamgia(tenMGG, phantram, ngaybatdau, ngayketthuc) values ("'.$this->tenMGG.'", '.$this->phantram.', "'.$this->ngaybatdau.'", "'.$this->ngayketthuc.'")';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Discount::isExist($this->idMGG, $this->tenMGG))){
                $sql = 'UPDATE magiamgia 
                        SET tenMGG = "'.$this->tenMGG.'",
                            phantram = '.$this->phantram.',
                            ngaybatdau = "'.$this->ngaybatdau.'",
                            ngayketthuc = "'.$this->ngayketthuc.'"
                        WHERE idMGG = '.$this->idMGG;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function delete(){
            $sql = 'DELETE FROM magiamgia WHERE idMGG = '.$this->idMGG;
            $con = new Database();
            $con->execute($sql);
        }

        function hide(){
            $sql = 'UPDATE magiamgia SET trangthai = "'.$this->trangthai.'" WHERE idMGG = '.$this->idMGG;
            $con = new Database();
            $con->execute($sql);
        }
        
        function toArray() {
            return [
                'idMGG' => $this->idMGG,
                'tenMGG' => $this->tenMGG,
                'phantram' => $this->phantram,
                'ngaybatdau' => $this->ngaybatdau,
                'ngayketthuc' => $this->ngayketthuc,
                'trangthai' => $this->trangthai
            ];
        }

        function setIdMGG($idMGG){
            $this->idMGG = $idMGG;
        }

        function setTenMGG($tenMGG){
            $this->tenMGG = $tenMGG;
        }

        function setPhantram($phantram){
            $this->phantram = $phantram;
        }

        function setNgaybatdau($ngaybatdau){
            $this->ngaybatdau = $ngaybatdau;
        }

        function setNgayketthuc($ngayketthuc){
            $this->ngayketthuc = $ngayketthuc;
        }

        function setTrangthai($trangthai){
            $this->trangthai = $trangthai;
        }

        function getIdMGG(){
            return $this->idMGG;
        }

        function getTenMGG(){
            return $this->tenMGG;
        }

        function getPhantram(){
            return $this->phantram;
        }

        function getNgaybatdau(){
            return $this->ngaybatdau;
        }

        function getNgayKetthuc(){
            return $this->ngayketthuc;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

    }
?>