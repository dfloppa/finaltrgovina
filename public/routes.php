<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Get the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Get the router
$router = $app->make('router');

// Get all routes
$routes = $router->getRoutes();

// Display routes
echo "<h1>All Routes</h1>";
echo "<table border='1'>";
echo "<tr><th>Method</th><th>URI</th><th>Name</th><th>Action</th></tr>";

foreach ($routes as $route) {
    echo "<tr>";
    echo "<td>" . implode('|', $route->methods()) . "</td>";
    echo "<td>" . $route->uri() . "</td>";
    echo "<td>" . $route->getName() . "</td>";
    echo "<td>" . $route->getActionName() . "</td>";
    echo "</tr>";
}

echo "</table>"; 