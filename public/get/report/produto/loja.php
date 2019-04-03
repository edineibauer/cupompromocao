<?php

use \Conn\Read;
use \Conn\SqlCommand;
use \Helpers\Check;
use \Report\Chart;

if (!empty($link->getVariaveis()[1])) {
    $read = new Read();
    $idLoja = (int)$link->getVariaveis()[1];
    $read->exeRead("lojas", "WHERE id = :id", "id={$idLoja}");
    if ($read->getResult()) {
        $loja = $read->getResult()[0];

        $chart = new Chart();
        $chart->setTitle("Loja {$loja['razao_social']}");
        $chart->setX("nome");
        $chart->setY("vendas");
        $chart->setType("pie");
        $chart->setTime($link->getVariaveis()[0] ?? 30);

        $produtos = [];
        $read->exeRead("produtos");
        if ($read->getResult()) {
            foreach ($read->getResult() as $p)
                $produtos[$p['id']] = $p['nome'];

            $dados = [];

            $whereDate = $chart->getWhereDate("v.data");

            $sql = new SqlCommand();
            $sql->exeCommand("SELECT l.*, l.produtos FROM " . PRE . "vendas as v JOIN " . PRE . "lancamentos as l JOIN " . PRE . "funcionarios as f ON v.lancamento = l.id AND v.funcionario = f.id "
            . (!empty($whereDate) ? $whereDate . " AND" : "WHERE ") . " f.loja = {$idLoja}" . " LIMIT 10");
            if ($sql->getResult()) {
                foreach ($sql->getResult() as $item) {
                    if (!empty($item['produtos']) && Check::isJson($item['produtos'])) {
                        foreach (json_decode($item['produtos'], !0) as $produto) {
                            if (!isset($dados[$produtos[$produto['produto']]]))
                                $dados[$produtos[$produto['produto']]] = ["vendas" => 0, "nome" => $produtos[$produto['produto']]];

                            $dados[$produtos[$produto['produto']]]['vendas'] += (int)$produto['quantidade'];
                        }
                    }
                }
            }
            $chart->setData($dados);
            $data['data'] = $chart->getData();
        }
    }
}