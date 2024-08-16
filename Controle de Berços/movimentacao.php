<?php
ob_start(); // Inicia o buffer de saída
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['user_id'];
    $action = $_POST['action'];
    $placa_caminhao = strtoupper($_POST['placa_caminhao']);
    $nome_motorista = strtoupper($_POST['nome_motorista']);
  //  $observacoes = $_POST['observacoes'];
    $bercos = isset($_POST['bercos']) ? $_POST['bercos'] : array();

    if (!is_array($bercos)) {
        $bercos = explode(',', $bercos);
    }

    if ($action == 'envio') {
        $filial_origem_id = $_POST['filial_origem_id'];
        $filial_destino_id = $_POST['filial_destino_id'];
        $movimentacao_ids = [];

        foreach ($bercos as $berco_id) {
            $berco_id = trim($berco_id);

            // Verificar se o berço está na filial de origem
            $verifica_stmt = $conn->prepare("SELECT filial_id FROM bercos WHERE id = ?");
            $verifica_stmt->bind_param("i", $berco_id);
            $verifica_stmt->execute();
            $verifica_result = $verifica_stmt->get_result();
            $berco = $verifica_result->fetch_assoc();

            if ($berco && $berco['filial_id'] == $filial_origem_id) {
                // Insere a movimentação no banco de dados
                $stmt = $conn->prepare("INSERT INTO movimentacoes (berco_id, filial_origem_id, filial_destino_id, placa_caminhao, nome_motorista, usuario_id, tipo) VALUES (?, ?, ?, ?, ?, ?, 'envio')");
                $stmt->bind_param("iiissi", $berco_id, $filial_origem_id, $filial_destino_id, $placa_caminhao, $nome_motorista, $id_usuario);
                if ($stmt->execute()) {
                    $movimentacao_id = $stmt->insert_id;
                    $movimentacao_ids[] = $movimentacao_id;

                    // Atualiza o status do berço para em trânsito
                    $update_stmt = $conn->prepare("UPDATE bercos SET filial_id = NULL WHERE id = ?");
                    $update_stmt->bind_param("i", $berco_id);
                    $update_stmt->execute();
                } else {
                    echo "Erro ao registrar movimentação: " . $stmt->error;
                }
            } else {
                echo "O berço {$berco_id} não está na filial de origem especificada.";
            }
        }

        // Gerar número da minuta
        $stmt = $conn->prepare("SELECT COALESCE(MAX(numero_minuta), 0) + 1 AS numero_minuta FROM minutas");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $numero_minuta = $row['numero_minuta'];

        // Inserir a minuta no banco de dados
        foreach ($movimentacao_ids as $movimentacao_id) {
            $stmt = $conn->prepare("INSERT INTO minutas (numero_minuta, movimentacao_id, placa_caminhao, nome_motorista, observacoes) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $numero_minuta, $movimentacao_id, $placa_caminhao, $nome_motorista, $observacoes);
            if (!$stmt->execute()) {
                echo "Erro ao inserir minuta: " . $stmt->error;
            }
        }

        // Redirecionar para a página da minuta com o número da minuta
        header("Location: minuta_envio.php?numero_minuta=$numero_minuta");
        exit();
    } elseif ($action == 'recebimento') {
        $filial_destino_id = $_POST['filial_destino_id'];
        $movimentacao_ids = [];

        foreach ($bercos as $berco_id) {
            $berco_id = trim($berco_id);

            // Verifica se o berço está em trânsito
            $verifica_stmt = $conn->prepare("SELECT filial_id FROM bercos WHERE id = ? AND filial_id IS NULL");
            $verifica_stmt->bind_param("i", $berco_id);
            $verifica_stmt->execute();
            $verifica_result = $verifica_stmt->get_result();
            $berco = $verifica_result->fetch_assoc();

            if ($berco) {
                // Insere a movimentação de recebimento no banco de dados
                $stmt = $conn->prepare("INSERT INTO movimentacoes (berco_id, filial_origem_id, filial_destino_id, placa_caminhao, nome_motorista, usuario_id, tipo) VALUES (?, NULL, ?, ?, ?, ?, 'recebimento')");
                $stmt->bind_param("iissi", $berco_id, $filial_destino_id, $placa_caminhao, $nome_motorista, $id_usuario);
                if ($stmt->execute()) {
                    // Atualiza a filial do berço para a nova filial
                    $update_stmt = $conn->prepare("UPDATE bercos SET filial_id = ? WHERE id = ?");
                    $update_stmt->bind_param("ii", $filial_destino_id, $berco_id);
                    if ($update_stmt->execute()) {
                        $movimentacao_ids[] = $stmt->insert_id;
                    } else {
                        echo "Erro ao atualizar berço: " . $update_stmt->error;
                    }
                } else {
                    echo "Erro ao registrar movimentação: " . $stmt->error;
                }
            } else {
                echo "O berço {$berco_id} não está em trânsito.";
            }
        }

        echo "Movimentação de recebimento registrada com sucesso!";
    }
}

// Consulta para obter os berços em trânsito
$bercos_transito = $conn->query("SELECT id, numero FROM bercos WHERE filial_id IS NULL");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Movimentação</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        input.uppercase {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">

<!--start logo-->
<img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
    <li><a href="dashboard.php">Tela Inicial</a></li>


        <h1>Registrar Movimentação</h1>
        <div class="movimentacao-buttons">
            <button id="btnEnvio" class="mov-btn">Registrar Envio</button>
            <button id="btnRecebimento" class="mov-btn">Registrar Recebimento</button>
        </div>
        <form id="movimentacaoForm" action="movimentacao.php" method="POST" style="display: none;">
            <input type="hidden" id="action" name="action" value="">
            <label for="filial_origem_id">Filial de Origem:</label>
            <select id="filial_origem_id" name="filial_origem_id">
                <?php
                $result = $conn->query("SELECT id, nome FROM filiais");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <label for="filial_destino_id">Filial de Destino:</label>
            <select id="filial_destino_id" name="filial_destino_id" required>
                <?php
                $result = $conn->query("SELECT id, nome FROM filiais");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <label for="placa_caminhao">Placa do Caminhão:</label>
            <input type="text" id="placa_caminhao" name="placa_caminhao" class="uppercase" required>
            <label for="nome_motorista">Nome do Motorista:</label>
            <input type="text" id="nome_motorista" name="nome_motorista" class="uppercase" required>
            <label for="observacoes">Observações:</label>
            <textarea id="observacoes" name="observacoes" rows="4"></textarea>
            <label for="bercos">Berços (separados por vírgula):</label>
            <input type="text" id="bercos" name="bercos" required>
            <button type="submit">Registrar Movimentação</button>
        </form>
        <div id="bercosTransito" style="display: none;">
            <h2>Berços em Trânsito</h2>
            <form id="receberForm" action="movimentacao.php" method="POST">
                <input type="hidden" name="action" value="recebimento">
                <label for="filial_destino_id_recebimento">Filial de Destino:</label>
                <select id="filial_destino_id_recebimento" name="filial_destino_id" required>
                    <?php
                    $result = $conn->query("SELECT id, nome FROM filiais");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                    }
                    ?>
                </select>
                <label for="placa_caminhao_recebimento">Placa do Caminhão:</label>
                <input type="text" id="placa_caminhao_recebimento" name="placa_caminhao" class="uppercase" required>
                <label for="nome_motorista_recebimento">Nome do Motorista:</label>
                <input type="text" id="nome_motorista_recebimento" name="nome_motorista" class="uppercase" required>
                <table>
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>ID</th>
                            <th>Número do Berço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $bercos_transito->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="bercos[]" value="<?php echo $row['id']; ?>"></td>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['numero']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit">Registrar Recebimento</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('btnEnvio').addEventListener('click', function () {
            document.getElementById('movimentacaoForm').style.display = 'block';
            document.getElementById('bercosTransito').style.display = 'none';
            document.getElementById('action').value = 'envio';
        });

        document.getElementById('btnRecebimento').addEventListener('click', function () {
            document.getElementById('movimentacaoForm').style.display = 'none';
            document.getElementById('bercosTransito').style.display = 'block';
            document.getElementById('action').value = 'recebimento';
        });
    </script>
</body>
</html>
