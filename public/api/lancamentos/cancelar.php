<?php

$read = new \Conn\Read();
$up = new \Conn\Update();
$sql = new \Conn\SqlCommand();
$data['data'] = [];

$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);
$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_DEFAULT);
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$sql->exeCommand("SELECT f.id FROM " . PRE . "funcionarios as f JOIN " . PRE . "usuarios as u ON f.usuarios_id = u.id WHERE u.token = '{$token}' AND u.status = 1");
if ($sql->getResult()) {
    $funcionario = $sql->getResult()[0]['id'];

    $read->exeRead("lancamentos", "WHERE id = :id && funcionario = :f", "id={$id}&f={$funcionario}");
    if ($read->getResult()) {
        $dados = $read->getResult()[0];
        if($dados['situacao'] === "2") {
            $dados['situacao'] = "4";
            $dados['descricao_do_problema'] = $mensagem;

            $retorno = \Entity\Entity::add("lancamentos", $dados);
            if (is_numeric($retorno))
                $data['data'] = 1;
            else
                $data['error'] = $retorno;
        } else {
            $data['error'] = "Lançamento só pode ser cancelado quando esta pendente.";
        }
    } else {
        $read->exeRead("lancamentos", "WHERE id = :id", "id={$id}");
        if ($read->getResult())
            $data['error'] = "Este Lançamento não lhe pertence.";
        else
            $data['error'] = "ID do Lançamento não encontrado.";
    }
}