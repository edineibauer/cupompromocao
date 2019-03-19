<?php

$data['data'] = "10000";

if(file_exists(PATH_HOME . "_cdn/store/historic.json")) {
    $historic = json_decode(file_get_contents(PATH_HOME . "_cdn/store/historic.json"), !0);
    $data['data'] = $historic['cesta'];
}