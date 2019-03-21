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

        $read->exeRead("funcionarios", "WHERE id = :ff", "ff={$dados['funcionario']}");
        if ($read->getResult()) {
            $loja = $read->getResult()[0]['loja'];

            //Busca lista de Campanhas que o funcionário participa
            $campanhasAceitas = [];
            $read->exeRead("campanhas_lojas");
            if ($read->getResult()) {
                foreach ($read->getResult() as $campanhas) {
                    $campanha = json_decode($campanhas['lojas'], !0);
                    if (is_array($campanha) && !empty($campanha)) {
                        foreach ($campanha as $item) {
                            if ($item['loja'] == $loja)
                                $campanhasAceitas[] = $campanhas['campanha'];
                        }
                    }
                }
            }

            $produtos = json_decode($dados['produtos'] ?? $dadosOldItem['produtos'], !0);
            $venda = [];
            foreach ($produtos as $produtoLancamento) {
                $read->exeRead("cesta");
                if ($read->getResult()) {
                    foreach ($read->getResult() as $cesta) {
                        if (in_array($cesta['campanha'], $campanhasAceitas)) {
                            $prod = json_decode($cesta['produtos_da_campanha'], !0);
                            foreach ($prod as $produtoCesta) {
                                if ($produtoCesta['produto'] == $produtoLancamento['produto']) {
                                    $read->exeRead("campanhas", "WHERE id =:id", "id={$cesta['campanha']}");
                                    if ($read->getResult() && $read->getResult()[0]['inicio_da_vigencia'] <= $dados['data_de_envio'] && ($read->getResult()[0]['termino_da_vigencia'] >= $dados['data_de_envio'] || (!empty($read->getResult()[0]['prazo_da_pendencia']) && $read->getResult()[0]['prazo_da_pendencia'] >= $dados['data_de_envio']))) {
                                        if (!isset($venda[$cesta['campanha']])) {
                                            $venda[$cesta['campanha']] = [
                                                "funcionario" => $dadosOldItem['funcionario'],
                                                "lancamento" => $dadosOldItem['id'],
                                                "data" => date("Y-m-d H:i:s"),
                                                "campanha" => $cesta['campanha'],
                                                "pontos" => $produtoLancamento['quantidade'] * $produtoCesta['pontos']
                                            ];
                                        } else {
                                            $venda[$cesta['campanha']]['pontos'] += $produtoLancamento['quantidade'] * $produtoCesta['pontos'];
                                        }
                                    }
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
                $read->exeRead("vendas", "WHERE lancamento = :l && campanha = :c", "l={$v['lancamento']}&c={$c}");
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
    } elseif ($dadosOldItem['situacao'] !== "1" && $dados["situacao"] === "1") {
        \Entity\Entity::delete("pendencias", ["lancamento" => $dadosOldItem['id']]);
        \Entity\Entity::delete("cancelamentos", ["lancamento" => $dadosOldItem['id']]);
        \Entity\Entity::delete("vendas", ["lancamento" => $dadosOldItem['id']]);
    }
}