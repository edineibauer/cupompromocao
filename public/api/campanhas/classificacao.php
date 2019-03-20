<?php

require_once PATH_HOME . 'public/api/campanhas/read.php';

if(!empty($data['data'])) {
    $campanhas = $data['data'];
    $data['data'] = [];
    foreach ($campanhas as $campanha) {
        $sql = new \Conn\SqlCommand();

        $sql->exeCommand("SELECT SUM(pontos) as total_pontos, funcionario, campanha FROM ". PRE ."vendas WHERE campanha = {$campanha['id']} GROUP BY funcionario ORDER BY total_pontos DESC");
        if($sql->getResult()) {
            $data['data'] = $sql->getResult();
        }
    }
}