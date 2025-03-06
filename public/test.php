<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Print some basic information
echo "PHP is working!<br>";
echo "PHP version: " . phpversion() . "<br>";

// Test database connection
try {
    $host = 'localhost';
    $db = 'finaltrgovina';
    $user = 'root';
    $pass = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!<br>";
    
    // Check products table
    $stmt = $pdo->query("SHOW COLUMNS FROM products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Products table columns:<br>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

// Check Laravel environment
echo "Laravel environment: " . (getenv('APP_ENV') ?: 'Not set') . "<br>";
echo "Laravel debug mode: " . (getenv('APP_DEBUG') ?: 'Not set') . "<br>";

// Memory usage
echo "Memory usage: " . memory_get_usage() . " bytes<br>";
echo "Peak memory usage: " . memory_get_peak_usage() . " bytes<br>"; 