<?php

$read = new \Conn\Read();
$data = ["error" => "", "response" => 1, "data" => ""];

foreach ($dadosOld as $dadosOldItem) {
    if ($dadosOldItem['situacao'] !== "2" && $dados['situacao'] === "2") {
        //registra pendência
        $pendencia = [
            "lancamento" => $dadosOldItem['id'],
            "descricao" => $dados['descricao_do_problema'],
            "data" => date("Y-m-d H:i:s")
        ];

        $read->exeRead("pendencias", "WHERE lancamento = :l", "l={$dadosOldItem['id']}");
        if (!$read->getResult()) {

            \Entity\Entity::delete("vendas", ["lancamento" => $dadosOldItem['id']]);
            \Entity\Entity::delete("cancelamentos", ["lancamento" => $dadosOldItem['id']]);

            $id = \Entity\Entity::add("pendencias", $pendencia);
            if (!is_numeric($id))
                $data['error'] = $id;
        }

    } elseif ($dadosOldItem['situacao'] !== "3" && $dados['situacao'] === "3") {

        $produtos = json_decode($dados['produtos'] ?? $dadosOldItem['produtos'], !0);
        $venda = [];
        foreach ($produtos as $produtoLancamento) {
            $read->exeRead("cesta");
            if ($read->getResult()) {
                foreach ($read->getResult() as $item) {
                    $campanha = $item['campanha'];
                    $prod = json_decode($item['produtos_da_campanha'], !0);
                    foreach ($prod as $produtoCesta) {
                        if ($produtoCesta['produto'] == $produtoLancamento['produto']) {
                            $read->exeRead("campanhas", "WHERE id =:id", "id={$campanha}");
                            if ($read->getResult() && $read->getResult()[0]['inicio_da_vigencia'] <= date("Y-m-d H:i:s") && $read->getResult()[0]['termino_da_vigencia'] >= date("Y-m-d H:i:s")) {
                                if (!isset($venda[$campanha])) {
                                    $venda[$campanha] = [
                                        "funcionario" => $dadosOldItem['funcionario'],
                                        "lancamento" => $dadosOldItem['id'],
                                        "data" => date("Y-m-d H:i:s"),
                                        "campanha" => $item['campanha'],
                                        "pontos" => $produtoLancamento['quantidade'] * $produtoCesta['pontos']
                                    ];
                                } else {
                                    $venda[$campanha]['pontos'] += $produtoLancamento['quantidade'] * $produtoCesta['pontos'];
                                }
                            }
                        }
                    }
                }
            }
        }

        \Entity\Entity::delete("pendencias", ["lancamento" => $dadosOldItem['id']]);
        \Entity\Entity::delete("cancelamentos", ["lancamento" => $dadosOldItem['id']]);

        if (!empty($venda)) {
            foreach ($venda as $c => $v) {
                $read->exeRead("vendas", "WHERE lancamento = :l", "l={$v['lancamento']}");
                if (!$read->getResult()) {
                    $id = \Entity\Entity::add("vendas", $v);
                    if (!is_numeric($id))
                        $data['error'] = $id;
                }
            }
        }

    } elseif ($dadosOldItem['situacao'] !== "4" && $dados["situacao"] === "4") {

        //cancelado pelo usuário
        $read->exeRead("cancelamentos", "WHERE lancamento = :l", "l={$dadosOldItem['id']}");
        if (!$read->getResult()) {

            \Entity\Entity::delete("pendencias", ["lancamento" => $dadosOldItem['id']]);
            \Entity\Entity::delete("vendas", ["lancamento" => $dadosOldItem['id']]);

            $id = \Entity\Entity::add("cancelamentos", ["lancamento" => $dadosOldItem['id'], "descricao" => $dados['descricao_do_problema'], "data" => date("Y-m-d H:i:s")]);
            if (!is_numeric($id))
                $data['error'] = $id;
        }
    } else {
        \Entity\Entity::delete("pendencias", ["lancamento" => $dadosOldItem['id']]);
        \Entity\Entity::delete("cancelamentos", ["lancamento" => $dadosOldItem['id']]);
        \Entity\Entity::delete("vendas", ["lancamento" => $dadosOldItem['id']]);
    }
}