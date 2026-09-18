<?php 
require 'vendor/autoload.php';

//use Dotenv\Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$servername  = $_ENV['servername'];
$username = $_ENV['username'];
$password = $_ENV['password'];
$dbname = $_ENV['dbname'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // Throw exception  on DB errors
    $pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);// Return rows as associative arrays
}catch(PDOException $current_error)
{
    echo "Connection Failed".$current_error;
    error_log($current_error);
    exit;
}

?>