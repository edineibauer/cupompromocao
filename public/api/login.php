<?php
$data = ['response' => 1, "error" => "", "data" => ""];

/*Obtém user*/
if (!empty($_POST['user']))
    $user = trim(strip_tags(filter_input(INPUT_POST, 'user', FILTER_DEFAULT)));
else
    $data['error'] = "parâmetro 'user' não recebido.";

/*Obtém senha*/
if (!empty($_POST['password']))
    $pass = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
else
    $data['error'] = "parâmetro 'password' não recebido.";

if (empty($data['error'])) {
    $login = new \Login\Login(['user' => $user, "password" => $pass], "funcionarios");

    if($login->getResult()) {
        $data['error'] = $login->getResult();
    } elseif(!empty($_SESSION['userlogin'])) {
        $data['data'] = $_SESSION['userlogin'];
        unset($data['data']['token_recovery']);
        unset($data['data']['token_expira']);
    } else {
        $data['error'] = "Erro ao executar login";
    }
}