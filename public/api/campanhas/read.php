<?php

$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

$read = new \Conn\Read();
$read->exeRead("usuarios", "WHERE token = :to", "to={$token}");
if ($read->getResult()) {
    if ($read->getResult()[0]['status']) {
        $data['data'] = [];
        $user = $read->getResult()[0];
        $read->exeRead("funcionarios", "WHERE usuarios_id = :ui", "ui={$user['id']}");
        if ($read->getResult()) {
            $user['funcionarios'] = $read->getResult()[0];
            $read->exeRead("lojas", "WHERE id = :li", "li={$user['funcionarios']['loja']}");
            if ($read->getResult())
                $user['loja'] = $read->getResult()[0];
        } else {
            $data['error'] = "Usuário não é um Funcionário";
        }

        if (!empty($user['loja']) && $user['loja']['ativo']) {
            $read->exeRead("campanhas_lojas");
            if ($read->getResult()) {
                foreach ($read->getResult() as $campanhas) {
                    $campanha = json_decode($campanhas['lojas'], !0);
                    if (is_array($campanha) && !empty($campanha)) {
                        foreach ($campanha as $item) {
                            if (isset($item['loja']) && isset($user['loja']) && $item['loja'] == $user['loja']['id']) {
                                $read->exeRead("campanhas", "WHERE id = :cc", "cc={$campanhas['campanha']}");
                                if ($read->getResult()) {
                                    $cc = $read->getResult()[0];
                                    $read->exeRead("premios", "WHERE id = :idp", "idp={$cc['tipo_da_premiacao']}");
                                    $cc['tipo_da_premiacao'] = $read->getResult() ? $read->getResult()[0]['descricao'] : "";
                                    $cc['divulgacao'] = date("d/m/Y H:i:s", strtotime($cc['divulgacao']));
                                    $cc['prazo_para_cadastro'] = date("d/m/Y H:i:s", strtotime($cc['prazo_para_cadastro']));
                                    $cc['inicio_da_vigencia'] = date("d/m/Y H:i:s", strtotime($cc['inicio_da_vigencia']));
                                    $cc['termino_da_vigencia'] = date("d/m/Y H:i:s", strtotime($cc['termino_da_vigencia']));
                                    $cc['data'] = date("d/m/Y H:i:s", strtotime($cc['data']));
                                    unset($cc['premios']);
                                    $data['data'][] = $cc;
                                }
                                break;
                            }
                        }
                    }
                }
            }
        }
    } else {
        $data['error'] = "Usuário Desativado!";
    }
} else {
    $data['error'] = "Token de Usuário Inválido";
}

if(!empty($data['data']))
    $data['data'] = ["campanhas" => $data['data']];