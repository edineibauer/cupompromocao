<?php

include_once PATH_HOME . VENDOR . 'cupompromocao/public/api/campanhas/read.php';

$read = new \Conn\Read();
$produtos = [];

if(empty($data['error']) && !empty($data['data'])) {
    foreach ($data['data'] as $datum) {
        $read->exeRead("cesta", "WHERE campanha = :idCampanha", "idCampanha={$datum['id']}");
        if($read->getResult() && !empty($read->getResult()[0]['produtos_da_campanha'])) {
            foreach (json_decode($read->getResult()[0]['produtos_da_campanha'], !0) as $item) {
                $read->exeRead("produtos", "WHERE id = :p", "p={$item['produto']}");
                if($read->getResult()) {
                    $p = $read->getResult()[0];
                    $produtos[] = ["id" => $p['id'], "codigo_de_barras" => $p['codigo_de_barras'], "nome" => $p['nome'], "pontos" => $item['pontos'], "campanha" => $datum['id']];
                }
            }
        }
    }
}

$data['data'] = ["produtos" => $produtos];