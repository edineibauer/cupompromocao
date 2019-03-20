<?php

$read = new \Conn\Read();
$data = ["error" => "", "response" => 1, "data" => ""];

foreach ($dadosOld as $dadosOldItem) {
    \Entity\Entity::delete("vendas", ["lancamento" => $dadosOldItem['id']]);
    \Entity\Entity::delete("cancelamentos", ["lancamento" => $dadosOldItem['id']]);
    \Entity\Entity::delete("pendencias", ["lancamento" => $dadosOldItem['id']]);
}