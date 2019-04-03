<?php

use \Conn\SqlCommand;
use \Helpers\Check;
use \Report\Chart;

$chart = new Chart();
$chart->setTitle("Lojas que mais Vendem");
$chart->setX("nome");
$chart->setY(["pontos", "vendas"]);
$chart->setType(["pontos" => "bar", "vendas" => "line"]);
$chart->setTime($link->getVariaveis()[0] ?? 30);

$dados = [];
$sql = new SqlCommand();
$sql->exeCommand("SELECT f.loja, v.pontos, l.razao_social, la.produtos FROM " . PRE . "vendas as v JOIN " . PRE . "funcionarios as f JOIN " . PRE . "lojas as l JOIN " . PRE . "lancamentos as la ON v.funcionario = f.id AND f.loja = l.id AND v.lancamento = la.id " . $chart->getWhereDate("v.data") . " LIMIT 10");
if ($sql->getResult()) {
    foreach ($sql->getResult() as $item) {
        if (!isset($dados[$item['loja']]))
            $dados[$item['loja']] = ["pontos" => 0, "vendas" => 0, "nome" => $item['razao_social']];

        $dados[$item['loja']]['pontos'] += (int)$item['pontos'];

        if (!empty($item['produtos']) && Check::isJson($item['produtos'])) {
            foreach (json_decode($item['produtos'], !0) as $produto)
                $dados[$item['loja']]['vendas'] += (int)$produto['quantidade'];
        }
    }
}
$chart->setData($dados);
$data['data'] = $chart->getData();
