<?php
session_start();
include 'db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Consulta SQL para obter os números dos berços em cada filial
$sql = "SELECT f.nome AS filial, b.numero AS numero_berco 
        FROM filiais f 
        LEFT JOIN bercos b ON f.id = b.filial_id 
        ORDER BY f.nome, b.numero";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berços por Filial</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!--start logo-->
    <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
        <h1>Berços por Filial</h1>
        <li><a href="dashboard.php">Tela Inicial</a></li>
        <table>
            <thead>
                <tr>
                    <th>Filial</th>
                    <th>Numeração dos Berços</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $current_filial = '';
                $bercos = [];

                while ($row = $result->fetch_assoc()) {
                    if ($current_filial != $row['filial']) {
                        if ($current_filial != '') {
                            echo "<tr><td>{$current_filial}</td><td>" . implode(', ', $bercos) . "</td></tr>";
                        }
                        $current_filial = $row['filial'];
                        $bercos = [];
                    }
                    $bercos[] = $row['numero_berco'];
                }
                if ($current_filial != '') {
                    echo "<tr><td>{$current_filial}</td><td>" . implode(', ', $bercos) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
