<?php

$read = new \Conn\Read();
$sql = new \Conn\SqlCommand();
$data['data'] = [];

$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$sql->exeCommand("SELECT f.id FROM " . PRE . "funcionarios as f JOIN " . PRE . "usuarios as u ON f.usuarios_id = u.id WHERE u.token = '{$token}' AND u.status = 1");
if ($sql->getResult()) {
    $id = $sql->getResult()[0]['id'];

    $read->exeRead("lancamentos", "WHERE funcionario = :id", "id={$id}");
    if ($read->getResult()) {
        foreach ($read->getResult() as $item) {
            if (!empty($id)) {
                if(!empty($item['prazo_da_pendencia'])) {
                    $dd = explode('-', $item['prazo_da_pendencia']);
                    $item['prazo_da_pendencia'] = $dd[2] . "/" . $dd[1] . "/" . $dd[0];
                }
                $data['data'][] = ["id" => $item['id'], "status" => $item['situacao'], "mensagem" => $item['descricao_do_problema'] ?? "", "prazo_da_pendencia" => $item['prazo_da_pendencia'] ?? ""];
            } elseif ($id === $item['id']) {
                $data['data'] = ["id" => $item['id'], "status" => $item['situacao']];
            }
        }
    }
}