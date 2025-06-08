<?php
include_once("code/conexao.php");
session_start();

$id_usuario = $_SESSION['id'];
$id_pedido = $_GET['id_pedido'] ?? null;

if (!$id_pedido) {
    echo "Pedido inválido.";
    exit;
}

// Verifica se o pedido pertence ao usuário
$stmt = $conexao->prepare("SELECT * FROM pedidos WHERE id_pedido = ? AND id_user = ?");
$stmt->bind_param("ii", $id_pedido, $id_usuario);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Busca atualizações
$stmt2 = $conexao->prepare("SELECT * FROM atualizacoes_pedido WHERE id_pedido = ? ORDER BY data_atualizacao DESC");
$stmt2->bind_param("i", $id_pedido);
$stmt2->execute();
$atualizacoes = $stmt2->get_result();
?>

<h1>Detalhes do Pedido: <?= htmlspecialchars($pedido['nome']) ?></h1>
<p>Status: <?= htmlspecialchars($pedido['status']) ?></p>
<p>Espécie: <?= htmlspecialchars($pedido['especie']) ?></p>
<p>Localidade: <?= htmlspecialchars($pedido['localidade']) ?></p>
<p>Data do Pedido: <?= htmlspecialchars($pedido['data_pedido']) ?></p>

<h2>Atualizações:</h2>
<ul>
<?php while ($row = $atualizacoes->fetch_assoc()) { ?>
    <li>
        <strong><?= $row['data_atualizacao'] ?>:</strong> <?= nl2br(htmlspecialchars($row['descricao'])) ?>
    </li>
<?php } ?>
</ul>
