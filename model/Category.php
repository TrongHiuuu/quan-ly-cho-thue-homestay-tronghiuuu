<?php
    class Category{
        private int $idDM;
        private string $tenDM;
        private int $trangthai;

        function nhap($tenDM, $trangthai, $idDM=0){
            $this->idDM = $idDM;
            $this->tenDM = $tenDM;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM danhmuc';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $category = new Category();
                $category->nhap($item['tenDM'], $item['trangthai'], $item['idDM']);
                $list[] = $category;
            }
            return $list;
        }

        static function getAllActive(){
            $list = [];
            $sql = 'SELECT DISTINCT * FROM danhmuc 
            WHERE danhmuc.trangthai = 1
            ';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $category = new Category();
                $category->nhap($item['tenDM'], $item['trangthai'], $item['idDM']);
                $list[] = $category;
            }
            return $list;
        }

        static function isExist($idDM, $tenDM){
            $sql = 'SELECT idDM FROM danhmuc WHERE tenDM= "'.$tenDM.'"';
            if($idDM!=0) $sql.=' AND idDM!='.$idDM;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idDM){
            $sql = 'SELECT * FROM danhmuc WHERE idDM='.$idDM;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $category = new Category();
                $category->nhap($req['tenDM'], $req['trangthai'], $req['idDM']);
                return $category;
            }
            return null;
        }

        function add(){
            if(!(Category::isExist($this->idDM, $this->tenDM))){
                $sql = 'INSERT INTO danhmuc(tenDM, trangthai) VALUES ("'.$this->tenDM.'", '.$this->trangthai.')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Category::isExist($this->idDM, $this->tenDM))){
                $sql = 'UPDATE danhmuc
                    SET tenDM = "'.$this->tenDM.'", trangthai = '.$this->trangthai.'
                    WHERE idDM = '.$this->idDM;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        static function search($kyw){
            $sql = 'SELECT idDM, tenDM, trangthai
                FROM danhmuc
                WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idDM LIKE "%'.$kyw.'%" OR tenDM LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $cat = new Category();
                $cat->nhap($item['tenDM'], $item['trangthai'], $item['idDM']);
                $list[] = $cat;
            }
            return $list;
        }

        static function getCategoryByIdBook($idSP) {
            $sql = 'SELECT danhmuc.idDM, tenDM, danhmuc.trangthai
                    FROM danhmuc
                        INNER JOIN sanpham on danhmuc.idDM = sanpham.idDM
                    WHERE sanpham.idSP='.$idSP.' AND danhmuc.trangthai=1';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $category = new self();
                $category->nhap($req['tenDM'], $req['trangthai'], $req['idDM']);
                return $category;
            }
            return null;
        }
        
        function toArray() {
            return [
                'idDM' => $this->idDM,
                'tenDM' => $this->tenDM,
                'trangthai' => $this->trangthai
            ];
        }

        function setidDM($idDM){
            $this->idDM = $idDM;
        }

        function settenDM($tenDM){
            $this->tenDM = $tenDM;
        }

        function setTrangthai($trangthai){
            $this->trangthai = $trangthai;
        }

        function getidDM(){
            return $this->idDM;
        }

        function gettenDM(){
            return $this->tenDM;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

    }
?>