<?php

$chave = md5(strtotime('now') . rand(0, 10000000));

$up = new \Conn\Update();
$up->exeUpdate("api_chave", ["chave" => $chave], "WHERE id = :id", "id={$dados['id']}");