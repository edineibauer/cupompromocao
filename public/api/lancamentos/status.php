<?php

$read = new \Conn\Read();
$sql = new \Conn\SqlCommand();
$data['data'] = [];

$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);
$idLancamento = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$sql->exeCommand("SELECT f.id FROM " . PRE . "funcionarios as f JOIN " . PRE . "usuarios as u ON f.usuarios_id = u.id WHERE u.token = '{$token}' AND u.status = 1");
if ($sql->getResult()) {
    $id = $sql->getResult()[0]['id'];

    if (!empty($idLancamento))
        $read->exeRead("lancamentos", "WHERE funcionario = :idf && id = :id", "idf={$id}&id={$idLancamento}");
    else
        $read->exeRead("lancamentos", "WHERE funcionario = :id", "id={$id}");

    if ($read->getResult()) {
        foreach ($read->getResult() as $item) {
            if (!empty($item['prazo_da_pendencia']))
                $item['prazo_da_pendencia'] = date("d/m/Y H:i:s", strtotime($item['prazo_da_pendencia']));
            $data['data'][] = ["id" => $item['id'], "status" => $item['situacao'], "mensagem" => $item['descricao_do_problema'] ?? "", "prazo_da_pendencia" => $item['prazo_da_pendencia'] ?? ""];
        }
    }
}