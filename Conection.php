<?php
class Conection {
    public $ambiente = true;
    private $dns = '';
    private $username = '';
    private $password = '';

    function conexao() {
        try {
            if($this->ambiente == true){
                $this->dns = 'mysql:host=localhost;dbname=db_controlescolar;charset=utf8';
                $this->username = 'root';
                $this->password = '1234';
                return new PDO($this->dns, $this->username, $this->password);
            }else if($this->ambiente == false){
                $this->dns = '';
                $this->username = '';
                $this->password = '';
                return new PDO($this->dns, $this->username, $this->password);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function simpleSelect($sql) {
        try {
            $coon = $this->conexao();
            
            $stm = $coon->prepare($sql);

            $stm->execute();

            return $stm;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function simpleSelect1($tb, $campo, $valor) {
        try {
            $coon = $this->conexao();
            
            $stm = $coon->prepare('select * from '.$tb.' where '.$campo.'='.$valor.';');

            $stm->execute();

            return $stm;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}