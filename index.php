<?php
require 'vendor/autoload.php';
include 'lib.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App as App;
use \Slim\Container as Container;
use Slim\Views\PhpRenderer as PhpRenderer;


$configuration = [
		'settings' => [
				'displayErrorDetails' => true,
		],
		'renderer' => new PhpRenderer("./templates")
];
$container = new Container($configuration);
$app = new App($container);

// Uses a PHP templating system to display HTML when requested
$app->get('/', function (Request $request, Response $response) {
    return $this->renderer->render($response, "/index.phtml");
});


$app->get("/projects", function(Request $request, Response $response){
	$projects = getAllProjects();
	
	$response = $response->withJson($projects);
	return $response;
});

$app->post("/projects", function(Request $request, Response $response){
	$post = $request->getParsedBody();
	$name = $post['name'];
	$price = $post['price'];
	$countryId = $post['country'];
	// print "Name: $name, Price:$price, Country: $countryId";
	$res = saveProject($name, $price, $countryId);
	// print ($res);
	if ($res > 0){
		$response = $response->withStatus(201);
		$response = $response->withJson(array( "id" => $res));
	} else {
		$response = $response->withStatus(400);
	}
	return $response;
});

$app->run();
?>

