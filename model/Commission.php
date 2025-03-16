<?php
    class Commission{
        private int $idMP;
        private float $phantram;
     

        function nhap(float $phantram, int $idMP=0){
            $this->phantram = $phantram;
            $this->idMP = $idMP;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM mucphi';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $commission = new Commission();
                $commission->nhap($item['phantram'], $item['idMP']);
                $list[] = $commission;
            }
            return $list;
        }

        static function isExist($idMP, $phantram){
            $sql = 'SELECT idMP FROM mucphi WHERE phantram= "'.$phantram.'"';
            if($idMP!=0) $sql.=' AND idMP!='.$idMP;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idMP){
            $sql = 'SELECT * FROM mucphi WHERE idMP='.$idMP;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $commission = new Commission();
                $commission->nhap($req['phantram'], $req['idMP']);
                return $commission;
            }
            return null;
        }

        function add(){
            if(!(Commission::isExist($this->idMP, $this->phantram))){
                $sql = 'INSERT INTO mucphi(idMP, phantram) VALUES ('.$this->idMP.', '.$this->phantram.')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Commission::isExist($this->idMP, $this->phantram))){
                $sql = 'UPDATE mucphi
                    SET phantram = '.$this->phantram.'
                    WHERE idMP = '.$this->idMP;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        static function search($kyw){
            $sql = 'SELECT idMP, phantram
                FROM mucphi
                WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idMP LIKE "%'.$kyw.'%" OR phantram LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $cat = new Commission();
                $cat->nhap($item['phantram'], $item['idMP']);
                $list[] = $cat;
            }
            return $list;
        }
        
        static function delete($idMP) {
            $con = new Database();

            // Đặt idMP về NULL trong bảng quan trước
            $updateSql = "UPDATE quan SET idMP = NULL WHERE idMP = ".$idMP;
            $con->execute($updateSql);

            // Xóa mức phí từ bảng mucphi
            $deleteSql = "DELETE FROM mucphi WHERE idMP = ".$idMP;
            $con->execute($deleteSql);
            return true;
        }

        function toArray() {
            return [
                'idMP' => $this->idMP,
                'phantram' => $this->phantram
            ];
        }

        function setIdMP($idMP){
            $this->idMP = $idMP;
        }

        function setPhantram($phantram){
            $this->phantram = $phantram;
        }

        function getIdMP(){
            return $this->idMP;
        }

        function getPhantram(){
            return $this->phantram;
        }

    }
?>