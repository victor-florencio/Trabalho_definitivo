<?php
include_once("code/conexao.php");
include_once("code/loginC.php"); 
// atualizar_status.php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['pedido_id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'error' => 'Parâmetros insuficientes']);
    exit;
}

$pedidoId = intval($data['pedido_id']);
$status = trim($data['status']);

// Conexão com seu banco (ajusta conforme o seu)
$mysqli = new mysqli('localhost', 'usuario', 'senha', 'banco');
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Erro na conexão: ' . $mysqli->connect_error]);
    exit;
}

// Atualiza o status no banco
$stmt = $mysqli->prepare("UPDATE pedidos SET status = ? WHERE id_pedido = ?");
$stmt->bind_param('si', $status, $pedidoId);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>
