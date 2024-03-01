<?php
header('Content-type: application/json charset=utf-8');

$response = array();
$list_row = array();

//$SenhaSegura = md5($Senha);

if ($_SERVER["REQUEST_METHOD"] == "POST") { //ver se o metodo é post
    require_once "./Conection.php";
    require_once "./cadastros.php";
    // Receber os dados enviado pelo JavaScript
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $cx = new Conection();
    $cad = new Cadastros();

    if ('listar-aluno-encarregado' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_aluno where fk_encarregado = ".$dados['id_encarregado'].";");

        if ($stm->rowCount() > 0) {
            while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                $stms =  $cx->simpleSelect("select * from tb_encarregado where id_encarregado = " . $ct->fk_encarregado);
                $encarregados =  $cx->simpleSelect("select * from tb_encarregado where id_encarregado = " . $ct->fk_encarregado);
                $turmas =  $cx->simpleSelect("select * from tb_turma where id_turma = " . $ct->fk_turma);
                $e = null;
                $t = null;
                while ($es = $encarregados->fetch(PDO::FETCH_OBJ)) {
                    $e = $es;
                }
                while ($ts = $turmas->fetch(PDO::FETCH_OBJ)) {
                    $t = $ts;
                }

                $list_row[] =
                    array(
                        "id_aluno" => $ct->id_aluno,
                        "nome" => $ct->nome,
                        "genero" => $ct->genero,
                        "nascimento" => $ct->nascimento,
                        "encarregado" => array(
                            "id_encarregado" => $e->id_encarregado,
                            "nome" => $e->nome,
                            "email" => $e->email,
                            "contacto" => $e->contacto,
                            "num_bi" => $e->num_bi,
                            "usuario" => $e->usuario,
                            "senha" => $e->senha
                        ),
                        "turma" => array(
                            "id_turma" => $t->id_turma,
                            "ano_letivo" => $t->ano_letivo,
                            "sala" => $t->sala,
                            "periodo" => $t->periodo,
                            "turma" => $t->turma,
                            "classe" => $t->classe
                        )
                    );
            }
        }

        echo json_encode($list_row);
    }
    
    if ('listar-teste' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_aluno;");

        if ($stm->rowCount() > 0) {
            while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                $stms =  $cx->simpleSelect("select * from tb_encarregado where id_encarregado = " . $ct->fk_encarregado);
                $encarregados =  $cx->simpleSelect("select * from tb_encarregado where id_encarregado = " . $ct->fk_encarregado);
                $turmas =  $cx->simpleSelect("select * from tb_turma where id_turma = " . $ct->fk_turma);
                $e = null;
                $t = null;
                while ($es = $encarregados->fetch(PDO::FETCH_OBJ)) {
                    $e = $es;
                }
                while ($ts = $turmas->fetch(PDO::FETCH_OBJ)) {
                    $t = $ts;
                }

                $list_row[] =
                    array(
                        "id_aluno" => $ct->id_aluno,
                        "nome" => $ct->nome,
                        "genero" => $ct->genero,
                        "nascimento" => $ct->nascimento,
                        "encarregado" => array(
                            "id_encarregado" => $e->id_encarregado,
                            "nome" => $e->nome,
                            "email" => $e->email,
                            "contacto" => $e->contacto,
                            "num_bi" => $e->num_bi,
                            "usuario" => $e->usuario,
                            "senha" => $e->senha
                        ),
                        "turma" => array(
                            "id_turma" => $t->id_turma,
                            "ano_letivo" => $t->ano_letivo,
                            "sala" => $t->sala,
                            "periodo" => $t->periodo,
                            "turma" => $t->turma,
                            "classe" => $t->classe
                        )
                    );
            }
        }

        echo json_encode($list_row);
    }

    if ('add-professor' == $dados['type']) {
        $coon = $cx->conexao();
        $isave = $cad->add_usprof($dados);

        if ($isave == true) {
            $stm =  $cx->simpleSelect1('tb_professor', 'num_bi', "'" . $dados['num_bi'] . "'");
            while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
                $list_row =
                    array(
                        "id_prof" => $e->id_prof,
                        "nome" => $e->nome,
                        "email" => $e->email,
                        "contacto" => $e->contacto,
                        "num_bi" => $e->num_bi,
                        "usuario" => $e->usuario,
                        "senha" => $e->senha,
                        "erro" => false
                    );
            }
            echo json_encode($list_row);
        } else {
            $this->list_row["erro"] = true;
            echo json_encode($list_row);
        }
    }
    
    if ('add-encarregado' == $dados['type']) {
        $coon = $cx->conexao();
        $isave = $cad->add_usencarregado($dados);

        if ($isave == true) {
            $stm =  $cx->simpleSelect1('tb_encarregado', 'num_bi', "'" . $dados['num_bi'] . "'");
            while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
                $list_row =
                    array(
                        "id_encarregado" => $e->id_encarregado,
                        "nome" => $e->nome,
                        "email" => $e->email,
                        "contacto" => $e->contacto,
                        "num_bi" => $e->num_bi,
                        "usuario" => $e->usuario,
                        "senha" => $e->senha,
                        "erro" => false
                    );
            }
            echo json_encode($list_row);
        } else {
            $this->list_row["erro"] = true;
            echo json_encode($list_row);
        }
    }

    if ('login-professor' == $dados['type']) {
        $coon = $cx->conexao();

        $stm =  $cx->simpleSelect1('tb_professor', 'usuario', "'" . $dados['usuario'] . "'");
        while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
            if ($e->senha == $dados['senha']) {
                $list_row =
                    array(
                        "id_prof" => $e->id_prof,
                        "nome" => $e->nome,
                        "email" => $e->email,
                        "contacto" => $e->contacto,
                        "num_bi" => $e->num_bi,
                        "usuario" => $e->usuario,
                        "senha" => $e->senha,
                        "erro" => false
                    );
            }
        }
        echo json_encode($list_row);
    }
    
    if ('login-encarregado' == $dados['type']) {
        $coon = $cx->conexao();

        $stm =  $cx->simpleSelect1('tb_encarregado', 'usuario', "'" . $dados['usuario'] . "'");
        while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
            if ($e->senha == $dados['senha']) {
                $list_row =
                    array(
                        "id_encarregado" => $e->id_encarregado,
                        "nome" => $e->nome,
                        "email" => $e->email,
                        "contacto" => $e->contacto,
                        "num_bi" => $e->num_bi,
                        "usuario" => $e->usuario,
                        "senha" => $e->senha,
                        "erro" => false
                    );
            }
        }
        echo json_encode($list_row);
    }

    if ('professor-disciplina' == $dados['type']) {
        $coon = $cx->conexao();

        $stm =  $cx->simpleSelect1('tb_prof_disciplina', 'fk_professor', $dados['id_professor']);
        while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
            $disciplinas =  $cx->simpleSelect1('tb_disciplina', 'id_disciplina', $e->fk_disciplina);
            while ($ds = $disciplinas->fetch(PDO::FETCH_OBJ)) {
                $list_row[] =
                    array(
                        "id_disciplina" => $ds->id_disciplina,
                        "descricao" => $ds->descricao,
                        "abreviacao" => $ds->abreviacao
                    );
            }
        }
        echo json_encode($list_row);
    }

    if ('professor-turma' == $dados['type']) {
        $coon = $cx->conexao();

        $stm =  $cx->simpleSelect1('tb_profturma', 'fk_professor', $dados['id_professor']);
        while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
            $turmas =  $cx->simpleSelect1('tb_turma', 'id_turma', $e->fk_turma);
            while ($ds = $turmas->fetch(PDO::FETCH_OBJ)) {
                $list_row[] =
                    array(
                        "id_turma" => $ds->id_turma,
                        "ano_letivo" => $ds->ano_letivo,
                        "sala" => $ds->sala,
                        "periodo" => $ds->periodo,
                        "turma" => $ds->turma,
                        "classe" => $ds->classe
                    );
            }
        }
        echo json_encode($list_row);
    }

    if ('add-mini-pauta' == $dados['type']) {
        $coon = $cx->conexao();
        $isave = $cad->add_minipauta($dados);

        if ($isave == true) {
            $stm =  $cx->simpleSelect1('tb_nota', 'fk_aluno', $dados['fk_aluno']);
            while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
                $list_row[] =
                    array(
                        "id_nota" => $e->id_nota,
                        "mac" => $e->mac,
                        "cpp" => $e->cpp,
                        "pt" => $e->pt,
                        "ct" => $e->ct,
                        "mac1" => $e->mac1,
                        "cpp1" => $e->cpp1,
                        "pt1" => $e->pt1,
                        "ct1" => $e->ct1,
                        "mac2" => $e->mac2,
                        "cpp2" => $e->cpp2,
                        "pt2" => $e->pt2,
                        "ct2" => $e->ct2,
                        "cap" => $e->cap,
                        "ce" => $e->ce,
                        "ca" => $e->ca,
                        "fk_disciplina" => $e->fk_disciplina,
                        "fk_aluno" => $e->fk_aluno,
                        "fk_prof" => $e->fk_prof,
                        "erro" => false
                    );
            }
            echo json_encode($list_row);
        } else {
            $list_row["erro"] = true;
            echo json_encode($list_row);
        }
    }

    if ('nota-aluno' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_nota where fk_aluno = " . $dados['fk_aluno'] . " and fk_disciplina = " . $dados['fk_disciplina']);
        while ($e = $stm->fetch(PDO::FETCH_OBJ)) {
            $list_row =
                array(
                    "id_nota" => $e->id_nota,
                    "mac" => $e->mac,
                    "cpp" => $e->cpp,
                    "pt" => $e->pt,
                    "ct" => $e->ct,
                    "mac1" => $e->mac1,
                    "cpp1" => $e->cpp1,
                    "pt1" => $e->pt1,
                    "ct1" => $e->ct1,
                    "mac2" => $e->mac2,
                    "cpp2" => $e->cpp2,
                    "pt2" => $e->pt2,
                    "ct2" => $e->ct2,
                    "cap" => $e->cap,
                    "ce" => $e->ce,
                    "ca" => $e->ca,
                    "fk_disciplina" => $e->fk_disciplina,
                    "fk_aluno" => $e->fk_aluno,
                    "fk_prof" => $e->fk_prof,
                    "erro" => false
                );
        }
        echo json_encode($list_row);
    }













































































































    /*
    if ('funcionario' == $dados['type']) {
        $salvou = $cad->add_usuario($dados);

        if($salvou == true){
            $coon = $cx->conexao();
            $stm =  $cx->simpleSelect("select * from tb_usuario;");
            
            if($stm->rowCount() > 0){
                $response["erro"] = false;
                $response["sms"] = "Funcionário foi salvo com sucesso.";
                while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                    $list_row = $list_row."<tr> <td style='display:none'>".$ct->id_usuario."</td> <td>".$ct->nome."</td> <td>".$ct->email."</td> <td>".$ct->contacto."</td> <td style='display:none'>".$ct->num_bi."</td><td>".$ct->usuario."</td><td style='display:none'>".$ct->senha."</td><td>".$ct->tipo."</td></tr>";
                }
            }else{
                $response["erro"] = true;
                $response["sms"] = "O usuário não foi salvo.";
            }
            $response["lista"] = $list_row;
        }else{
            $response["sms"] = "O usuário não foi salvo.";
            $response["$salvou"] = $salvou;
        }
        
        echo json_encode($response);
    }

    if ('listar-funcionario' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_usuario;");
        
        if($stm->rowCount() > 0){
            $response["erro"] = false;
            while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                $list_row = $list_row."<tr> <td style='display:none'>".$ct->id_usuario."</td> <td>".$ct->nome."</td> <td>".$ct->email."</td> <td>".$ct->contacto."</td> <td style='display:none'>".$ct->num_bi."</td><td>".$ct->usuario."</td><td style='display:none'>".$ct->senha."</td><td>".$ct->tipo."</td></tr>";
            }
        }else{
            $response["erro"] = true;
            $response["sms"] = "A lista está vazia.";
        }
        $response["lista"] = $list_row;
        
        echo json_encode($response);
    }
    
    if ('listar-encarregado' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_encarregado;");
        
        if($stm->rowCount() > 0){
            $response["erro"] = false;
            while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                $list_row = $list_row."<tr> <td>".$ct->id_encarregado."</td> <td>".$ct->nome."</td> <td>".$ct->email."</td> <td>".$ct->contacto."</td> <td>".$ct->num_bi."</td></tr>";
                $list_row2[] = array("id"=>$ct->id_encarregado, "nome"=>$ct->nome, "email"=>$ct->email, "contacto"=>$ct->contacto, "num_bi"=>$ct->num_bi);
            }
        }else{
            $response["erro"] = true;
            $response["sms"] = "A lista está vazia.";
        }
        $response["lista"] = $list_row;
        $response["lista_encarregado"] = $list_row2;
        
        echo json_encode($response);
    }

    if ('add-aluno' == $dados['type']) {
        if($dados["fk_encarregado"] == null){
            $salvou = $cad->add_encarregado($dados);
            if($salvou == true){
                $stm =  $cx->simpleSelect("select * from tb_encarregado where nome = '".$dados["nome_enc"]."';");
                if($stm->rowCount() > 0){
                    while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                        $dados["fk_encarregado"] = $ct->id_encarregado;
                    }
                }
            }
        }
        $salvou = $cad->add_aluno($dados);

        if($salvou == true){
            $coon = $cx->conexao();
            $stm =  $cx->simpleSelect("select * from tb_aluno;");
            
            if($stm->rowCount() > 0){
                $response["erro"] = false;
                $response["sms"] = "Aluno foi salvo com sucesso.";
                while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                    $list_row = $list_row."<tr> <td style='display:none'>".$ct->id_aluno."</td> <td>".$ct->nome."</td> <td>".$ct->genero."</td> <td>".$ct->nascimento."</td> <td style='display:none'>".$ct->classe."</td><td>".$ct->turma."</td><td>".$ct->periodo."</td><td style='display:none'>".$ct->fk_encarregado."</td></tr>";
                }
            }else{
                $response["erro"] = true;
                $response["sms"] = "O aluno não foi salvo.";
            }
            $response["lista"] = $list_row;
        }else{
            $response["sms"] = "O aluno não foi salvo.";
            $response["salvou"] = $salvou." nascimento ".$dados["nascimento"];
        }
        
        echo json_encode($response);
    }
    
    if ('listar-aluno' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_aluno;");
        
        if($stm->rowCount() > 0){
            $response["erro"] = false;
            while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                $list_row = $list_row."<tr> <td style='display:none'>".$ct->id_aluno."</td> <td>".$ct->nome."</td> <td>".$ct->genero."</td> <td>".$ct->nascimento."</td> <td style='display:none'>".$ct->classe."</td><td>".$ct->turma."</td><td>".$ct->periodo."</td><td style='display:none'>".$ct->fk_encarregado."</td></tr>";
            }
        }else{
            $response["erro"] = true;
            $response["sms"] = "A lista está vazia.";
        }
        $response["lista"] = $list_row;
        
        echo json_encode($response);
    }

    if ('login' == $dados['type']) {
        $coon = $cx->conexao();
        $stm =  $cx->simpleSelect("select * from tb_usuario where usuario = '".$dados['username']."';");
        if($stm->rowCount() > 0){
            while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
                if($dados['password'] == $ct->senha){
                    $response["erro"] = false;
                    $response["sms"] = "Seja bem vindo ao gestor escolar sr/a ".$ct->usuario;
                    $response[] = array("id_usuario"=>$ct->id_usuario, "usuario"=>$ct->usuario);
                }else{
                    $response["erro"] = true;
                    $response["sms"] = "A senha ou usuário estão incorectas, tente novamente.";
                }
            }
        }
        echo json_encode($response);
    }
    
    if ('encarregado' == $dados['type']) {
        $cad->add_encarregado($dados);
    }*/
} else {
    require_once "./Conection.php";
    require_once "./cadastros.php";
    $cx = new Conection();
    $cad = new Cadastros();

    $coon = $cx->conexao();
    $stm =  $cx->simpleSelect("select * from tb_aluno;");

    if ($stm->rowCount() > 0) {
        while ($ct = $stm->fetch(PDO::FETCH_OBJ)) {
            $encarregados =  $cx->simpleSelect("select * from tb_encarregado where id_encarregado = " . $ct->fk_encarregado);
            $turmas =  $cx->simpleSelect("select * from tb_turma where id_turma = " . $ct->fk_turma);
            $e = null;
            $t = null;
            while ($es = $encarregados->fetch(PDO::FETCH_OBJ)) {
                $e = $es;
            }
            while ($ts = $turmas->fetch(PDO::FETCH_OBJ)) {
                $t = $ts;
            }
            $list_row[] =
                array(
                    "id_aluno" => $ct->id_aluno,
                    "nome" => $ct->nome,
                    "genero" => $ct->genero,
                    "nascimento" => $ct->nascimento,
                    "encarregado" => array(
                        "id_encarregado" => $e->id_encarregado,
                        "nome" => $e->nome,
                        "email" => $e->email,
                        "contacto" => $e->contacto,
                        "num_bi" => $e->num_bi,
                        "usuario" => $e->usuario,
                        "senha" => $e->senha
                    ),
                    "turma" => array(
                        "id_turma" => $t->id_turma,
                        "ano_letivo" => $t->ano_letivo,
                        "sala" => $t->sala,
                        "periodo" => $t->periodo,
                        "turma" => $t->turma,
                        "classe" => $t->classe
                    )
                );
        }
    }

    echo json_encode($list_row);
}
