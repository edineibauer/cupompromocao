<?php

use \Conn\Read;
use \Conn\SqlCommand;
use \Report\Chart;

if (!empty($link->getVariaveis()[1])) {
    $read = new Read();
    $idCampanha = (int)$link->getVariaveis()[1];
    $read->exeRead("campanhas", "WHERE id = :id", "id={$idCampanha}");
    if ($read->getResult()) {
        $campanha = $read->getResult()[0];

        $chart = new Chart();
        $chart->setTitle("Campanha {$campanha['nome']}");
        $chart->setX("nome");
        $chart->setY("pontos");
        $chart->setType("bar");
        $chart->setTime("all");

        $dados = [];
        $sql = new SqlCommand();
        $sql->exeCommand("SELECT SUM(v.pontos) as pontos, f.nome FROM " . PRE . "vendas as v JOIN " . PRE . "funcionarios as f ON v.funcionario = f.id"
            . " WHERE v.campanha = {$idCampanha} GROUP BY v.funcionario ORDER BY pontos DESC LIMIT 10");
        if ($sql->getResult()){
            foreach ($sql->getResult() as $item)
                $dados[] = ["nome" => $item['nome'], "pontos" => (int) $item['pontos']];
        }

        $chart->setData($dados);
        $data['data'] = $chart->getData();
    }
}