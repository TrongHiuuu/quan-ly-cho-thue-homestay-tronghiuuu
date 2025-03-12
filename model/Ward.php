<?php
    class Ward{
        private int $idQuan;
        private string $tenQuan;
        private ?int $idMP;

        function __construct()
        {
            $this->idQuan = 0;
            $this->tenQuan = '';
            $this->idMP = 0;
        }

        function nhap(int $idQuan, string $tenQuan, ?int $idMP=0){
            $this->idQuan = $idQuan;
            $this->tenQuan = $tenQuan;
            $this->idMP = $idMP;
        }
        
        static function findByID(int $idQuan){
            $sql = 'SELECT * FROM xa WHERE idQuan='.$idQuan;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $ward = new self();
                $ward->nhap($req['idQuan'], $req['tenQuan'], $req['idMP']);
                return $ward;
            }
            return null;
        }

        static function find(string $tenQuan, int $idQuan){
            $sql = 'SELECT * FROM quan WHERE tenQuan LIKE "%'.$tenQuan.'%" AND idQuan = '.$idQuan;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $ward = new self();
                $ward->nhap($req['idQuan'], $req['tenQuan'], $req['idMP']);
                return $ward;
            }
            return null;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * 
            FROM quan
            ORDER BY tenQuan ASC';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $ward = new self();
                $ward->nhap($item['idQuan'], $item['tenQuan'], $item['idMP']);
                $list[] = $ward->toArray();
            }
            return $list;
        }

        static function getAllByidMP(int $idMP) {
            $list = [];
            $sql = 'SELECT * 
            FROM quan 
            WHERE idMP = '.$idMP.'
            ORDER BY tenQuan ASC';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $ward = new self();
                $ward->nhap($item['idQuan'], $item['tenQuan'], $item['idMP']);
                $list[] = $ward->toArray();
            }
            return $list;
        }

        function toArray(){
            return [
                'idQuan' => $this->idQuan,
                'tenQuan' => $this->tenQuan,
                'idMP' => $this->idMP
            ];
        }

        function getTenQuan(){
            return $this->tenQuan;
        }

        function getIdQuan(){
            return $this->idQuan;
        }
    }
?>