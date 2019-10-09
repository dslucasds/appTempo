<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$baseUrl = 'http://api.openweathermap.org';
$appid = '28757e8aa2f8541ea37845f36b7f5a1d';
$id = '3468879';

//recuperar a data de criação dos dados
$dataCriacao = file_get_contents('cache/validade_tempo.txt');

if (time() - $dataCriacao >= 300) {


    try {
        $client = new Client(array('base_uri' => $baseUrl));

        $response = $client->get('data/2.5/weather', array(
            'query' => array('appid' => $appid, 'id' => $id)
        ));

        $tempo = json_decode($response->getBody());
        $dadosSerializados = serialize($tempo);
        file_put_contents('cache/dados_tempo.txt', $dadosSerializados);
        file_put_contents('cache/validade_tempo.txt', time());

       
    } catch (ClientException $e) {
        echo 'Falha ao obter informações';
    }
} else {
    $dadosSerializados = file_get_contents('cache/dados_tempo.txt');
    $tempo = unserialize($dadosSerializados);
} 

echo 'Temperatura: ' . ($tempo->main->temp - 273) . "<br/>";
        echo 'Pressão: ' . ($tempo->main->pressure) . "<br/>";
        echo 'Humidade: ' . ($tempo->main->humidity) . "<br/>";
        echo 'Mínima: ' . ($tempo->main->temp_min) . "<br/>";
        echo 'Máxima: ' . ($tempo->main->temp_max) . "<br/>";
?>