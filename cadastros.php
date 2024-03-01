<?php
require_once "./conexao.php";
class Cadastros {

    public $response = array();

    function __construct() {
        $this->response["erro"] = true;
    }
    
    public function add_usuario($data) {
        try {
            $cx = new Conexao();
            $conn = $cx->conexao();
            $stm = $conn->prepare("insert into tb_usuario(nome,email,contacto,num_bi,usuario,senha,tipo)values (?,?,?,?,?,?,?);");
            $stm->bindValue(1, $data['nome']);
            $stm->bindValue(2, $data["email"]);
            $stm->bindValue(3, $data["contacto"]);
            $stm->bindValue(4, $data["num_bi"]);
            $stm->bindValue(5, $data["usuario"]);
            $stm->bindValue(6, $data["senha"]);
            //$stm->bindValue(2, md5($data['senha']));
            $stm->bindValue(7, $data["tipo"]);

            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Throwable $th) {
            return false;
        }
    }
    
    public function add_aluno($data) {
        try {
            $cx = new Conexao();
            $conn = $cx->conexao();
            $stm = $conn->prepare("insert into tb_aluno(nome,genero,nascimento,classe,turma,periodo,fk_encarregado)values (?,?,?,?,?,?,?);");
            $stm->bindValue(1, $data['nome']);
            $stm->bindValue(2, $data["genero"]);
            $stm->bindValue(3, $data["nascimento"]);
            $stm->bindValue(4, $data["classe"]);
            $stm->bindValue(5, $data["turma"]);
            $stm->bindValue(6, $data["periodo"]);
            $stm->bindValue(7, $data["fk_encarregado"]);

            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Throwable $th) {
            return false;
        }
    }
    
    public function add_minipauta($data) {
        try {
            $cx = new Conexao();
            $conn = $cx->conexao();
            $stm = null;
            if($data["id_nota"] == 0){
                $stm = $conn->prepare("insert into tb_nota(mac,cpp,pt,mac1,cpp1,pt1,mac2,cpp2,pt2,ce,fk_aluno,fk_prof,
                fk_disciplina)values (?,?,?,?,?,?,?,?,?,?,?,?,?);");
            }else if($data["id_nota"] > 0){
                $stm = $conn->prepare("update tb_nota set mac=?,cpp=?,pt=?,mac1=?,cpp1=?,pt1=?,mac2=?,cpp2=?,pt2=?,ce=?,fk_aluno=?,fk_prof=?,
                fk_disciplina=? where id_nota=?;");
            }
            
            $stm->bindValue(1, $data['mac']);
            $stm->bindValue(2, $data["cpp"]);
            $stm->bindValue(3, $data["pt"]);

            $stm->bindValue(4, $data["mac1"]);
            $stm->bindValue(5, $data["cpp1"]);
            $stm->bindValue(6, $data["pt1"]);

            $stm->bindValue(7, $data["mac2"]);
            $stm->bindValue(8, $data["cpp2"]);
            $stm->bindValue(9, $data["pt2"]);
            $stm->bindValue(10, $data["ce"]);

            $stm->bindValue(11, $data["fk_aluno"]);
            $stm->bindValue(12, $data["fk_prof"]);
            $stm->bindValue(13, $data["fk_disciplina"]);
            if($data["id_nota"] > 0){
                $stm->bindValue(14, $data["id_nota"]);
            }

            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Throwable $th) {
            return false;
        }
    }

    function add_encarregado($data) {
        try {
            $cx = new Conexao();
            $conn = $cx->conexao();
            $sql = "insert into tb_encarregado( nome, email, contacto, num_bi )values (?, ?, ?, ?);";
            $stm = $conn->prepare($sql);
            $stm->bindValue(1, $data['nome_enc']);
            $stm->bindValue(2, $data['email']);
            $stm->bindValue(3, $data['contacto']);
            $stm->bindValue(4, $data['num_bi']);

            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Throwable $th) {
            $this->response["sms"] = "Ouve um erro.";
            $this->response["erro"] = true;
            echo json_encode($this->response);
        }
    }
    
    public function add_usencarregado($data){
        try {
            $cx = new Conexao();
            $conn = $cx->conexao();
            $id_encarregado = $cx->getId('tb_encarregado', 'id_encarregado', 'num_bi', $data['num_bi']);

            $stm = $conn->prepare("update tb_encarregado set usuario=?, senha=? where id_encarregado=?;");
            $stm->bindValue(1, $data['usuario']);
            $stm->bindValue(2, $data['senha']);
            $stm->bindValue(3, $id_encarregado);

            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        }catch (Throwable $th) {
            $this->response["sms"] = "Ouve um erro.";
            $this->response["erro"] = true;
            echo json_encode($this->response);
        }
    }

    public function add_usprof($data){
        try {
            $cx = new Conexao();
            $conn = $cx->conexao();
            $id_professor = $cx->getId('tb_professor', 'id_prof', 'num_bi', $data['num_bi']);

            $stm = $conn->prepare("update tb_professor set usuario=?, senha=? where id_prof=?;");
            $stm->bindValue(1, $data['usuario']);
            $stm->bindValue(2, $data['senha']);
            $stm->bindValue(3, $id_professor);

            if ($stm->execute()) {
                return true;
            } else {
                return false;
            }
        }catch (Throwable $th) {
            $this->response["sms"] = "Ouve um erro.";
            $this->response["erro"] = true;
            echo json_encode($this->response);
        }
    }
}
