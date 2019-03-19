<?php

$lancamento = [];
$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

$read = new \Conn\Read();
$read->exeRead("usuarios", "WHERE token = :tt", "tt={$token}");
if ($read->getResult()) {
    $read->exeRead("funcionarios", "WHERE usuarios_id = :ui", "ui={$read->getResult()[0]['id']}");
    if ($read->getResult()) {
        $lancamento['funcionario'] = $read->getResult()[0]['id'];
        $lancamento['codigo_do_cupom_fiscal'] = filter_input(INPUT_POST, 'cupom_fiscal', FILTER_DEFAULT);
        $lancamento['codigo_ccf'] = filter_input(INPUT_POST, 'codigo_ccf', FILTER_DEFAULT);
        $lancamento['data_e_hora_da_venda'] = filter_input(INPUT_POST, 'data_da_venda', FILTER_DEFAULT);
        $lancamento['cupom_anexo'] = filter_input(INPUT_POST, 'cupom_anexo', FILTER_DEFAULT);
        $lancamento['produtos'] = filter_input(INPUT_POST, 'produtos', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        foreach ($lancamento as $col => $value) {
            if (empty($value))
                $data['error'] = "Informe um valor para o campo '{$col}'";
        }
    }
} else {
    $data['error'] = "Token de usuário inválido!";
}

if (empty($data['error'])) {
    //create lancamento
}