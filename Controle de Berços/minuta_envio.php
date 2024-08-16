<?php
include 'db.php';

if (!isset($_GET['numero_minuta'])) {
    die('Número da minuta não fornecido.');
}

$numero_minuta = $_GET['numero_minuta'];

// Consulta para obter os detalhes da minuta
$sql = "SELECT 
            m.numero_minuta, 
            mov.filial_origem_id, 
            mov.filial_destino_id, 
            mov.placa_caminhao, 
            mov.nome_motorista, 
            mov.data_movimentacao, 
            mov.usuario_id, 
            u.nome AS usuario_nome, 
            GROUP_CONCAT(DISTINCT b.numero ORDER BY b.numero ASC SEPARATOR ', ') AS numero_bercos,
            m.observacoes
        FROM minutas m
        JOIN movimentacoes mov ON m.movimentacao_id = mov.id
        JOIN bercos b ON mov.berco_id = b.id
        JOIN usuarios u ON mov.usuario_id = u.id
        WHERE m.numero_minuta = ?
        GROUP BY m.numero_minuta, mov.filial_origem_id, mov.filial_destino_id, mov.placa_caminhao, mov.nome_motorista, mov.data_movimentacao, mov.usuario_id, u.nome, m.observacoes";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $numero_minuta);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Minuta não encontrada.');
}

$minuta = $result->fetch_assoc();

$filial_origem = $minuta['filial_origem_id'];
$filial_destino = $minuta['filial_destino_id'];

// Obtendo os nomes das filiais
$sql_filial = "SELECT id, nome FROM filiais WHERE id IN (?, ?)";
$stmt_filial = $conn->prepare($sql_filial);
$stmt_filial->bind_param("ii", $filial_origem, $filial_destino);
$stmt_filial->execute();
$result_filial = $stmt_filial->get_result();

$filiais = [];
while ($row = $result_filial->fetch_assoc()) {
    $filiais[$row['id']] = $row['nome'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minuta de Envio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">

     <!--start logo-->
     <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
    <li><a href="dashboard.php">Tela Inicial</a></li>
    
        <h1>Minuta de Envio</h1>
        <table>
            <tr>
                <th>Número da Minuta</th>
                <td><?php echo $minuta['numero_minuta']; ?></td>
            </tr>
            <tr>
                <th>Filial Origem</th>
                <td><?php echo $filiais[$filial_origem]; ?></td>
            </tr>
            <tr>
                <th>Filial Destino</th>
                <td><?php echo $filiais[$filial_destino]; ?></td>
            </tr>
            <tr>
                <th>Placa do Caminhão</th>
                <td><?php echo $minuta['placa_caminhao']; ?></td>
            </tr>
            <tr>
                <th>Nome do Motorista</th>
                <td><?php echo $minuta['nome_motorista']; ?></td>
            </tr>
            <tr>
                <th>Número dos Berços</th>
                <td><?php echo $minuta['numero_bercos']; ?></td>
            </tr>
            <tr>
                <th>Nome do Usuário</th>
                <td><?php echo $minuta['usuario_nome']; ?></td>
            </tr>
            <tr>
                <th>Data de Criação</th>
                <td><?php echo date('d/m/Y H:i:s', strtotime($minuta['data_movimentacao'])); ?></td>
            </tr>
            <tr>
                <th>Observações</th>
                <td><?php echo $minuta['observacoes']; ?></td>
            </tr>
        </table>
        <br>
        <div class="signatures">
            
            <div>
                <p>______________________________________</p>
                <p>Assinatura do Recebedor</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
