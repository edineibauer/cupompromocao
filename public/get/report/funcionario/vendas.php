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
        $chart->setY(["pontos", "vendas"]);
        $chart->setType(["pontos" => "bar", "vendas" => "line"]);
        $chart->setTime($link->getVariaveis()[0] ?? 30);

        $dados = [];
        $read = new Read();
        $read->exeRead("produtos");
        if ($read->getResult()) {
            $produtos = [];
            foreach ($read->getResult() as $p)
                $produtos[$p['id']] = $p['nome'];

            $whereDate = $chart->getWhereDate("v.data");
            $whereDate = (!empty($whereDate) ? $whereDate . " AND" : "WHERE ") . " f.loja = {$idLoja}";

            $sql = new SqlCommand();
            $sql->exeCommand("SELECT v.data, v.pontos, l.produtos, v.funcionario, f.nome"
                . " FROM " . PRE . "vendas as v "
                . " JOIN " . PRE . "lancamentos as l"
                . " JOIN " . PRE . "funcionarios as f"
                . " ON v.lancamento = l.id AND v.funcionario = f.id " . $whereDate . " LIMIT 10");

            if ($sql->getResult()) {
                foreach ($sql->getResult() as $item) {
                    if (!isset($dados[$item['funcionario']]))
                        $dados[$item['funcionario']] = ["vendas" => 0, "pontos" => 0, "nome" => $item['nome']];

                    $dados[$item['funcionario']]['pontos'] += (int) $item['pontos'];
                    if (!empty($item['produtos']) && Check::isJson($item['produtos'])) {
                        foreach (json_decode($item['produtos'], !0) as $produto)
                            $dados[$item['funcionario']]['vendas'] += (int) $produto['quantidade'];
                    }
                }
            }
        }

        $chart->setData($dados);
        $data['data'] = $chart->getData();
    }
}