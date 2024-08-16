<?php
ob_start(); // Inicia o buffer de saída
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ultimo_berco_numero = null;

// Obter o número do último berço cadastrado
$result = $conn->query("SELECT numero FROM bercos ORDER BY id DESC LIMIT 1");
if ($result->num_rows > 0) {
    $ultimo_berco = $result->fetch_assoc();
    $ultimo_berco_numero = $ultimo_berco['numero'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_berco = $_POST['numero_berco'];
    $filial_id = $_POST['filial_id'];

    // Verificar se o número do berço já existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM bercos WHERE numero = ?");
    $stmt->bind_param("i", $numero_berco);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Erro: Número do berço já existe.";
    } else {
        $sql = "INSERT INTO bercos (numero, filial_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $numero_berco, $filial_id);

        if ($stmt->execute()) {
            // Use ob_start() no início do script para evitar problemas de cabeçalho
            echo "Berço cadastrado com sucesso!";
            header("Location: cadastrar_berco.php");
            ob_end_flush(); // Envia o conteúdo do buffer de saída
            exit();
        } else {
            echo "Erro ao cadastrar berço: " . $stmt->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Berço</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!--start logo-->
    <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
        <h1>Cadastrar Berço</h1>
        <?php if ($ultimo_berco_numero): ?>
            <p>Último berço cadastrado: <?php echo $ultimo_berco_numero; ?></p>
        <?php endif; ?>
        <form id="cadastrar-berco-form" action="cadastrar_berco.php" method="POST">
            <label for="numero-berco">Número do Berço:</label>
            <input type="number" id="numero-berco" name="numero_berco" required>
            <label for="filial_id">Filial:</label>
            <select id="filial_id" name="filial_id" required>
                <?php
                // Inclua db.php novamente para garantir que a conexão ainda esteja ativa
                include 'db.php';
                $result = $conn->query("SELECT id, nome FROM filiais");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <button type="submit">Cadastrar</button>
            <li><a href="dashboard.php">Tela Inicial</a></li>
        </form>
    </div>
</body>
</html>
