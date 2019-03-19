<?php

$token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

$read = new \Conn\Read();
$read->exeRead("usuarios", "WHERE token = :to", "to={$token}");
if($read->getResult()) {
    $data['data'] = [];
    $user = $read->getResult()[0];
    $read->exeRead("funcionarios", "WHERE usuarios_id = :ui", "ui={$user['id']}");
    if($read->getResult()) {
        $user['funcionarios'] = $read->getResult()[0];
        $read->exeRead("lojas", "WHERE id = :li", "li={$user['funcionarios']['loja']}");
        if($read->getResult())
            $user['loja'] = $read->getResult()[0];
    } else {
        $data['error'] = "Usuário não é um Funcionário";
    }

    if(!empty($user['loja'])) {
        $read->exeRead("campanhas_lojas");
        if($read->getResult()) {
            foreach ($read->getResult() as $campanhas) {
                $campanha = json_decode($campanhas['lojas'], !0);
                if(is_array($campanha) && !empty($campanha)) {
                    foreach ($campanha as $item) {
                        if($item['loja'] == $user['loja']['id']) {
                            $read->exeRead("campanhas", "WHERE id = :cc", "cc={$campanhas['campanha']}");
                            if($read->getResult()) {
                                $cc = $read->getResult()[0];
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
    $data['error'] = "Token de Usuário Inválido";
}