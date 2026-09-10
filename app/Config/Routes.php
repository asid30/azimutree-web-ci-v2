<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Repositori Data archived: see docs/archive/. Keep controller URLs inaccessible.
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');
$routes->get('panduan', 'Home::panduan');
$routes->get('template', 'Home::template');
$routes->get('about', 'Home::about');
$routes->get('easteregg/mybestie', 'Easteregg::mybestie');
$routes->match(['GET','POST'], 'easteregg/secret', 'Easteregg::secret');
