<?php
date_default_timezone_set('America/Sao_Paulo');

// Conexão com o banco de dados
$servername = "sql5c75a.carrierzone.com";
$username = "tubaraotra695030";
$password = "Tubr215174@";
$dbname = "controlebercos_tubaraotra695030";


// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Definir o fuso horário do MySQL
$conn->query("SET time_zone = '-03:00'");
?>


