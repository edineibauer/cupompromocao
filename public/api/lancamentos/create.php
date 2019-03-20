<?php

$lancamento = [];
$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

$read = new \Conn\Read();
$read->exeRead("usuarios", "WHERE token = :tt", "tt={$token}");
if ($read->getResult()) {
    $read->exeRead("funcionarios", "WHERE usuarios_id = :ui", "ui={$read->getResult()[0]['id']}");
    if ($read->getResult()) {
        $lancamento['funcionario'] = $read->getResult()[0]['id'];
        $lancamento['codigo_do_cupom_fiscal'] = filter_input(INPUT_POST, 'codigo_do_cupom_fiscal', FILTER_DEFAULT);
        $lancamento['codigo_ccf'] = filter_input(INPUT_POST, 'codigo_ccf', FILTER_DEFAULT);
        $lancamento['data_e_hora_da_venda'] = filter_input(INPUT_POST, 'data_e_hora_da_venda', FILTER_DEFAULT);
        $lancamento['cupom_anexo'] = filter_input(INPUT_POST, 'cupom_anexo', FILTER_DEFAULT);
        $lancamento['produtos'] = filter_input(INPUT_POST, 'produtos', FILTER_DEFAULT);
        $lancamento['situacao'] = 1;

        $lancamento['cupom_anexo'] = $lancamento['produtos'];

        if(\Helpers\Check::isJson($lancamento['produtos'])) {
            $produtoTest = json_decode($lancamento['produtos'], !0);
            if(!is_array($produtoTest) || empty($produtoTest[0]['id']) || empty($produtoTest[0]['quantidade']))
                $data['error'] = "produto não é um array de produtos com [id, quantidade]";
        } else {
            $data['error'] = "produtos não é um JSON";
        }

        foreach ($lancamento as $col => $value) {
            if (empty($value))
                $data['error'] = [$col => "Preencha este campo"];
        }

        if (empty($data['error'])) {
            //create lancamento
            if(preg_match('/^\d\d\/\d\d\/\d\d\d\d\s/i', $lancamento['data_e_hora_da_venda'])) {
                $hora = explode(" ", $lancamento['data_e_hora_da_venda']);
                $date = explode("/", $hora[0]);
                $lancamento['data_e_hora_da_venda'] = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $hora[1];
            }

            $response = \Entity\Entity::add("lancamentos", $lancamento);

            if (is_numeric($response))
                $data['data'] = $response;
            else
                $data['error'] = $response;
        }
    } else {
        $data['error'] = "Usuário não encontrado";
    }
} else {
    $data['error'] = "Token de usuário inválido!";
}