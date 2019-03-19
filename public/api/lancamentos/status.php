<?php

$read = new \Conn\Read();
$sql = new \Conn\SqlCommand();
$data['data'] = [];

$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

$sql->exeCommand("SELECT f.id FROM " . PRE . "funcionarios as f JOIN " . PRE . "usuarios as u ON f.usuarios_id = u.id WHERE u.token = '{$token}' AND u.status = 1");
if($sql->getResult()) {
    $id = $sql->getResult()[0]['id'];

    $read->exeRead("lancamentos", "WHERE funcionario = :id", "id={$id}");
    if($read->getResult()) {
        foreach ($read->getResult() as $item)
            $data['data'][] = ["id" => $item['id'], "status" => $item['situacao']];
    } else {
        $data['error'] = "ID do Lançamento não encontrado.";
    }
}