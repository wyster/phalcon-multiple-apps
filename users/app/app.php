<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

$orders = new MicroCollection();

$orders->setHandler(new IndexController());
$orders->post('/', 'index');

$app->mount($orders);

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
