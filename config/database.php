<?php

use App\Database\Database;

$dns = 'mysql:host=localhost;dbname=apibalance';
$usuario = 'root';
$senha = '';

$pdo = new PDO($dns, $usuario, $senha);

return new Database($pdo);
