<?php
    class Utility{
        private int $idTI;
        private string $icon;
        private string $tenTI;
        private int $trangthai;

        function nhap($tenTI, $icon, $trangthai, $idTI=0){
            $this->idTI = $idTI;
            $this->tenTI = $tenTI;
            $this->icon = $icon;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM tienich';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $utility = new Utility();
                $utility->nhap($item['tenTI'], $item['icon'], $item['trangthai'], $item['idTI']);
                $list[] = $utility;
            }
            return $list;
        }

        static function isExist($idTI, $tenTI){
            $sql = 'SELECT idTI FROM tienich WHERE tenTI= "'.$tenTI.'"';
            if($idTI!=0) $sql.=' AND idTI!='.$idTI;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idTI){
            $sql = 'SELECT * FROM tienich WHERE idTI='.$idTI;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $utility = new Utility();
                $utility->nhap($req['tenTI'], $req['icon'], $req['trangthai'], $req['idTI']);
                return $utility;
            }
            return null;
        }

        function add(){
            if(!(Utility::isExist($this->idTI, $this->tenTI))){
                $sql = 'INSERT INTO tienich(tenTI, icon, trangthai) VALUES ("'.$this->tenTI.'", "'.$this->icon.'", '.$this->trangthai.')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Utility::isExist($this->idTI, $this->tenTI))){
                $sql = 'UPDATE tienich
                    SET tenTI = "'.$this->tenTI.'", trangthai = '.$this->trangthai.', icon = "'.$this->icon.'"
                    WHERE idTI = '.$this->idTI;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        static function search($kyw){
            $sql = 'SELECT idTI, tenTI, icon, trangthai
                FROM tienich
                WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idTI LIKE "%'.$kyw.'%" OR tenTI LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $cat = new Utility();
                $cat->nhap($item['tenTI'], $item['icon'], $item['trangthai'], $item['idTI']);
                $list[] = $cat;
            }
            return $list;
        }
        
        function toArray() {
            return [
                'idTI' => $this->idTI,
                'tenTI' => $this->tenTI,
                'icon' => $this->icon,
                'trangthai' => $this->trangthai
            ];
        }

        function setIdTI($idTI){
            $this->idTI = $idTI;
        }

        function setTenTI($tenTI){
            $this->tenTI = $tenTI;
        }

        function setIcon($icon) {
            $this->icon = $icon;
        }

        function setTrangthai($trangthai){
            $this->trangthai = $trangthai;
        }

        function getIdTI(){
            return $this->idTI;
        }

        function getTenTI(){
            return $this->tenTI;
        }

        function getIcon () {
            return $this->icon;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

    }
?>