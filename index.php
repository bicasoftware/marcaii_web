<?php
include './controllers/FirstSyncController.php';
include './controllers/FullResyncController.php';
include './controllers/SortedDataController.php';
include './controllers/HorasController.php';
include './controllers/SalariosController.php';
include './controllers/PorcentagemController.php';

$loader = require './vendor/autoload.php';

$app = new \Slim\Slim(array('templates.path' => 'templates'));

$app->get('/', function() use($app){
    echo json_encode(array('message'=>"empty set"));
});


//sincroniza dados vindos de um usuário que antes não tinha cadastro
$app->get('/firstsync/:id_user/:json', function($id_user, $json) use($app){
    (new \controllers\FirstSyncController($app))->syncNReturn($id_user, $json);
});

/**
  retorna todas as informações de um user
 * empregos, horas, salarios, horas
 *  */
$app->get('/syncall/:id_user', function($id_user) use($app){
	echo "Teste";
    (new \controllers\FullResyncController($app))->listAllDataByUser($id_user);
});

//cadastra novos empregos, novas horas, salários, porcentagens
// e compara os dados locais com os enviados para verificar alterações
$app->get('/sortedresync/:id_user/:json', function($id_client, $json) use($app){
    (new \controllers\SortedDataController($app))->SyncNReturn($id_client, $json);
});

/** INSERT*/
$app->post('/horas/:id_user/:id_emprego/:json', function($id_user, $id_emprego, $json) use($app) {
    (new \controllers\HorasController($app))->insert($id_user, $id_emprego, $json);
});

$app->post('/salarios/:id_user/:id_emprego/:json', function($id_client, $id_emprego, $json) use($app) {
    (new \controllers\SalariosController($app))->insert($id_client, $id_emprego, $json);
});

$app->post('/porcentagem/:id_user/:id_emprego/:json', function($id_client, $id_emprego, $json) use($app) {
    (new \controllers\PorcentagemController($app))->insert($id_client, $id_emprego, $json);
});

/**DELETE*/
$app->delete('/horas/:id_user/:id_emprego/:id_hora', function($id_client, $id_emprego, $id_hora) use($app){
    (new \controllers\HorasController($app))->delete($id_client, $id_emprego, $id_hora);
});

$app->delete('/salarios/:id_user/:id_emprego/:id_salario', function($id_client, $id_emprego, $id_salario) use($app){
    (new \controllers\SalariosController($app))->delete($id_client, $id_emprego, $id_salario);
});

$app->delete('/porcentagem/:id_user/:id_emprego/:id_porc', function($id_client, $id_emprego, $id_porc) use($app){
    (new \controllers\PorcentagemController($app))->delete($id_client, $id_emprego, $id_porc);
});

$app->run();
