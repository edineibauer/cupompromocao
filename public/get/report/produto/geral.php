<?php

use \Conn\Read;
use \Conn\SqlCommand;
use \Helpers\Check;
use \Report\Chart;

$chart = new Chart();
$chart->setTitle("Produtos Mais Vendidos");
$chart->setX("nome");
$chart->setY("vendas");
$chart->setType("pie");
$chart->setTime($link->getVariaveis()[0] ?? 30);

$dados = [];
$read = new Read();
$read->exeRead("produtos");
if ($read->getResult()) {
    $produtos = [];
    foreach ($read->getResult() as $p)
        $produtos[$p['id']] = $p['nome'];

    $sql = new SqlCommand();
    $sql->exeCommand("SELECT l.*, l.produtos FROM " . PRE . "vendas as v JOIN " . PRE . "lancamentos as l ON v.lancamento = l.id " . $chart->getWhereDate("v.data") . " LIMIT 10");
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