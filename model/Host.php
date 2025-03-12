<?php
    class Host{
        private int $idTK;
        private string $sodu;
        private ?string $stk;
        private float $sosao;

        function __construct()
        {
            $this->idTK = 0;
            $this->sodu = '';
            $this->stk = '';
            $this->sosao = 0.0;
        }
    
        function nhap(string $sodu, ?string $stk, float $sosao, int $idTk = 0){
            $this->idTK = $idTK;
            $this->sodu = $sodu;
            $this->stk = $stk;
            $this->sosao = $sosao;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM host';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $host = new self();
                $host->nhap( $item['sodu'], $item['stk'], $item['sosao'], $item['idTK']);
                $list[] = $host;
            }
            return $list;
        }

        static function getAllForAccount(){
            $list = [];
            $sql = 'SELECT * FROM nhomquyen';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $host = new Host();
                $host->nhap($item['idTK'], $item['sodu'], $item['stk']);
                $list[] = $host;
            }
            return $list;
        }

        static function getAllActive(){
            $list = [];
            $sql = 'SELECT * FROM nhomquyen WHERE stk=1';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $host = new Host();
                $host->nhap($item['idTK'], $item['sodu'], $item['stk']);
                $list[] = $host->toArrayNQ();
            }
            return $list==null ? null: $list;
        }

        static function isExist($idTK, $sodu){
            $sql = 'SELECT * FROM nhomquyen WHERE sodu = "'.$sodu.'"';
            if($idTK!=0) $sql.=' AND idTK!='.$idTK;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function search($kyw){
            $sql = 'SELECT DISTINCT idTK, sodu, stk
                    FROM nhomquyen
                    WHERE idTK != 1';
            if($kyw != NULL) $sql .= ' AND (idTK LIKE "%'.$kyw.'%" OR sodu LIKE "%'.$kyw.'%")';
            $list = [];
                $con = new Database();
                $req = $con->getAll($sql);
                foreach($req as $item){
                    $ronaldo = new Host();
                    $ronaldo->nhap($item['idTK'], $item['sodu'], $item['stk']);
                    $list[] = $ronaldo;
                }
                return $list;
        }

        static function findByID($idTK){
            $sql = 'SELECT * FROM nhomquyen WHERE idTK='.$idTK;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $host = new Host();
                $host->nhap($req['idTK'], $req['sodu'], $req['stk']);
                $host->getDetail();
                return $host;
            }
            return null;
        }

        function getDetail(){
            $sql = 'SELECT * FROM ctnhomquyen
                    INNER JOIN chucnang ON ctnhomquyen.idCN = chucnang.idCN
                    WHERE idTK='.$this->idTK;
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $permission = new Permission($item['idCN'], $item['tenCN']);
                $this->sosao[] = $permission;
            }
        }

        function add($permission_name){
            $msg = '';
            if(!(self::isExist($this->idTK, $this->sodu))){
                // kiem tra co chon chuc nang chua // except 'btn_submit_add'
                $n = count($permission_name)-1;
                if($n>0){
                    // tao moi nhom quyen
                    $sql = 'INSERT INTO nhomquyen(sodu, stk) VALUE("'.$this->sodu.'", '.$this->stk.')';
                    $con = new Database();
                    $con->execute($sql);
                    //getLastID
                    $this->idTK = $this->getLastID();
                    //addDetail
                    
                    $idCN = [];
                    for($i = 0; $i < $n; $i++){
                        $permission = Permission::findByName($permission_name[$i]);
                        $idCN[] = $permission->getIdCN();
                    }
                    $this->addDetail($idCN);
                }
                else $msg = 'Vui lòng chọn chức năng';
                
            }
            else $msg = 'Nhóm quyền đã tồn tại';
            return $msg;
        }

        function getLastID(){
            $sql = 'SELECT idTK
            FROM nhomquyen
            ORDER BY idTK DESC
            LIMIT 1';
            $con = new Database();
            return $con->getOne($sql)['idTK'];
        }

        function addDetail(array $idCN){
            $sql = 'INSERT INTO ctnhomquyen(idTK, idCN) VALUE';
            foreach($idCN as $item)
                $sql .= '('.$this->idTK.','.$item.'),';
            $sql = rtrim($sql, ',');
            $con = new Database();
            $con->execute($sql);
        }

        function update($permission_name){
            if(!(self::isExist($this->idTK, $this->sodu))){
                $sql = 'UPDATE nhomquyen SET sodu = "'.$this->sodu.'" WHERE idTK ='.$this->idTK;
                $con = new Database();
                $con->execute($sql);
                //revoke permission
                $this->revokePermission();
                //addDetail
                $n = count($permission_name)-1; // except 'btn_submit_add'
                for($i = 0; $i < $n; $i++){
                    $permission = Permission::findByName($permission_name[$i]);
                    $idCN[] = $permission->getIdCN();
                }
                $this->addDetail($idCN);
                return true;
            }
            return false;
        }

        function revokePermission(){
            $sql = 'DELETE FROM ctnhomquyen WHERE idTK ='.$this->idTK;
            $con = new Database();
            $con->execute($sql);
        }

        function lock(){
            $sql = 'UPDATE nhomquyen SET stk = '' WHERE idTK='.$this->idTK;
            $con = new Database();
            $con->execute($sql);
        }

        function unlock(){
            $sql = 'UPDATE nhomquyen SET stk = 1 WHERE idTK='.$this->idTK;
            $con = new Database();
            $con->execute($sql);
        }
        
        function toArray() {
            return [
                'host' => $this->toArrayNQ(),
                'role_detail' => $this->toArrayDetail()
            ];
        }

        function toArrayDetail(){
            $list = [];
            foreach($this->sosao as $item)
                $list[] = $item->toArray();
            return $list;
        }

        function toArrayNQ(){
            return [
                'idTK' => $this->idTK,
                'sodu' => $this->sodu,
                'stk' => $this->stk
            ];
        }
        
        function setIdNQ($idTK){
            $this->idTK = $idTK;
        }

        function setTenNQ($sodu){
            $this->sodu = $sodu;
        }

        function setTrangthai($stk){
            $this->stk = $stk;
        }

        function getIdNQ(){
            return $this->idTK;
        }

        function getTenNQ(){
            return $this->sodu;
        }

        function getTrangthai(){
            return $this->stk;
        }

    }
?>