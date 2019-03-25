<?php

include_once PATH_HOME . VENDOR . 'cupompromocao/public/api/campanhas/read.php';

if(!empty($data['data']['campanhas'])) {
    $campanhas = $data['data']['campanhas'];
    $data['data'] = [];
    foreach ($campanhas as $campanha) {
        $sql = new \Conn\SqlCommand();

        $sql->exeCommand("SELECT SUM(pontos) as total_pontos, funcionario, campanha FROM ". PRE ."vendas WHERE campanha = {$campanha['id']} GROUP BY funcionario ORDER BY total_pontos DESC");
        if($sql->getResult()) {
            foreach ($sql->getResult() as $i => $item) {
                if($item['funcionario'] == $user['funcionarios']['id']) {
                    unset($item['funcionario']);
                    $data['data'][] = [
                        "classificacao" => $i+1,
                        "pontos" => (int)$item['total_pontos'],
                        "campanha" => (int)$campanha['id']
                    ];
                }
            }
        }
    }
}

if(!empty($data['data']))
    $data['data'] = ["classificacao" => $data['data']];