<?php

$read = new \Conn\Read();
$data = ["error" => "", "response" => 1, "data" => ""];

\Entity\Entity::delete("vendas", ["lancamento" => $dados['id']]);
\Entity\Entity::delete("cancelamentos", ["lancamento" => $dados['id']]);
\Entity\Entity::delete("pendencias", ["lancamento" => $dados['id']]);