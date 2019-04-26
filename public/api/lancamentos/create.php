<?php

$lancamento = [];
$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

$read = new \Conn\Read();
$read->exeRead("usuarios", "WHERE token = :tt", "tt={$token}");
if ($read->getResult()) {
    if ($read->getResult()[0]['status']) {
        $read->exeRead("funcionarios", "WHERE usuarios_id = :ui", "ui={$read->getResult()[0]['id']}");
        if ($read->getResult()) {
            $lancamento['funcionario'] = $read->getResult()[0]['id'];
            $lancamento['codigo_do_cupom_fiscal'] = filter_input(INPUT_POST, 'codigo_do_cupom_fiscal', FILTER_DEFAULT);
            $lancamento['codigo_ccf'] = filter_input(INPUT_POST, 'codigo_ccf', FILTER_DEFAULT);
            $lancamento['data_e_hora_da_venda'] = filter_input(INPUT_POST, 'data_e_hora_da_venda', FILTER_DEFAULT);
            $cupom_anexo = filter_input(INPUT_POST, 'cupom_anexo', FILTER_DEFAULT);
            $prod = filter_input(INPUT_POST, 'produtos', FILTER_DEFAULT);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $lancamento['situacao'] = 1;
            $lancamento['data_de_envio'] = date("Y-m-d");
            $lancamento['produtos'] = [];

            if (\Helpers\Check::isJson($prod)) {
                $produtoTest = json_decode($prod, !0);
                if (empty($produtoTest['produtos']) || !is_array($produtoTest) || empty($produtoTest['produtos'][0]['id']) || empty($produtoTest['produtos'][0]['quantidade'])) {
                    $data['error'] = "produto não é um array de produtos com [id, quantidade]";
                }else {
                    foreach ($produtoTest['produtos'] as $produto) {
                        $read->exeRead("produtos", "WHERE id = :id", "id={$produto['id']}");
                        if($read->getResult()) {
                            $lancamento['produtos'][] = [
                                "columnName" => "produtos",
                                "columnRelation" => "produtos_lancamento",
                                "columnStatus" => ["have" => "false", "value" => "false", "column" => ""],
                                "columnTituloExtend" => "<small class='color-gray left opacity padding-tiny'>quantidade</small><span style='padding-left:5px' class='left padding-right td-number'> {$produto["quantidade"]}</span><small class='color-gray left opacity padding-tiny'>nome</small><span style='padding-left:5px' class='left padding-right td-title'> {$read->getResult()[0]['nome']}</span>",
                                "formIdentificador" => "8751553178269646",
                                "id" => strtotime("now") . rand(9999999, 99999999),
                                "produto" => $produto['id'],
                                "quantidade" => $produto['quantidade']
                            ];
                        }
                    }
                }
            } else {
                $data['error'] = "produtos não é um JSON";
            }

            foreach ($lancamento as $col => $value) {
                if (empty($value))
                    $data['error'] = [$col => "Preencha este campo"];
            }

            if (empty($data['error'])) {

                //corrige formato da data recebida
                if (preg_match('/^\d\d\/\d\d\/\d\d\d\d\s/i', $lancamento['data_e_hora_da_venda'])) {
                    $hora = explode(" ", $lancamento['data_e_hora_da_venda']);
                    $date = explode("/", $hora[0]);
                    $lancamento['data_e_hora_da_venda'] = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $hora[1];
                }

                //preenche objeto cupom
                $lancamento['cupom_anexo'] = [];
                if(!empty($cupom_anexo)) {

                    // Decode base64 data AND create image
                    list($type, $data) = explode(';', $cupom_anexo);
                    list(, $data) = explode(',', $data);
                    $file_data = base64_decode(str_replace(' ', "+", $data));
                    $isImage = preg_match('/^data:image\//i', $type);
                    $dir = "uploads/form/" . date("Y") . "/" . date("m") . "/";
                    $imageUrl = strtotime('now') . rand(9999999, 99999999) . "-" . strtotime('now') . ".jpg";
                    file_put_contents(PATH_HOME . $dir . $imageUrl, $file_data);

                    $lancamento['cupom_anexo'][] = [
                        "nome" => "Cupom Anexo",
                        "name" => "cupom-anexo",
                        "type" => "jpeg",
                        "fileType" => "image/jpeg",
                        "size" => "934596",
                        "sizeName" => "934KB",
                        "url" => HOME . $dir . $imageUrl,
                        "data" => date("H:i d/m/Y"),
                        "preview" => "<img src='" . HOME . $dir . $imageUrl . "' alt='' title='Imagem Anexo' class='left radius'/>",
                        "image" => HOME . $dir . $imageUrl
                    ];
                }

                //checa se é uma atualização
                if (!empty($id) && is_numeric($id) && $id > 0) {
                    $read->exeRead("lancamentos", "WHERE id =:id && situacao = 2", "id={$id}");
                    if ($read->getResult()) {
                        $prazo_alteracao = $read->getResult()[0]['prazo_da_pendencia'];
                        if ($prazo_alteracao >= date("Y-m-d")) {
                            $del = new \Conn\Delete();
                            $del->exeDelete("lancamentos", "WHERE id =:id && situacao = 2", "id={$id}");
                            $lancamento['prazo_da_pendencia'] = $prazo_alteracao;
                            $lancamento['data_de_envio'] = date("Y-m-d");
                            $lancamento['situacao'] = 1;

                            $response = \Entity\Entity::add("lancamentos", $lancamento);
                        } else {
                            $data['error'] = "Prazo para Alteração Esgotado.";
                        }
                    } else {
                        $data['error'] = "Este Lançamento não existe no Sistema.";
                    }
                } else {
                    $response = \Entity\Entity::add("lancamentos", $lancamento);
                }

                if (is_numeric($response))
                    $data = ['response' => 1, 'data' => ["id" => (string) $response], 'error' => ''];
                else
                    $data['error'] = $response;
            }
        } else {
            $data['error'] = "Usuário não é um Funcionário";
        }
    } else {
        $data['error'] = "Usuário Desativado!";
    }
} else {
    $data['error'] = "Token de usuário inválido!";
}