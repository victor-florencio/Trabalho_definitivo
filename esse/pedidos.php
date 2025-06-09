<?php
include_once("code/conexao.php");
include_once("code/loginC.php"); 

if (!isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id']; 

// A query busca os pedidos do usuário e os ordena pela data mais recente
$sql = "SELECT * FROM pedidos WHERE id_user = $id_usuario ORDER BY data_pedido DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos | Treedom</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3a7d44;
            --secondary: #2d5a34;
            --accent: #5cb85c;
            --bg-light: #f4f8f4;
            --surface-light: #ffffff;
            --text-dark: #2c3e50;
            --text-medium: #586a7a;
            --border-light: #e0e6e9;
            --blue: #3498db;
            --orange: #f39c12;
            --grey: #95a5a6;
            --item-shadow: rgba(0, 0, 0, 0.06);
            --item-hover-shadow: rgba(0, 0, 0, 0.1);
        }
        
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            background-color: var(--bg-light);
            line-height: 1.6;
        }

        /* Nav & Footer */
        nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: sticky; top: 0; z-index: 1000; padding: 12px 0;
            border-bottom: 1px solid var(--border-light);
        }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .logo { font-family: 'Playfair Display', serif; font-size: 1.9rem; font-weight: 700; color: var(--primary); text-decoration: none; }
        footer {
            background: var(--surface-light); color: var(--text-medium);
            padding: 25px 20px; text-align: center; margin-top: 50px;
            border-top: 1px solid var(--border-light); font-size: 0.9rem;
        }

        /* Layout Principal */
        .page-container {
            max-width: 1000px; /* Largura ajustada para uma única coluna */
            margin: 30px auto;
            padding: 0 20px;
        }

        .header-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: var(--secondary);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-light);
            text-align: center;
        }

        /* Grid de Pedidos */
        .orders-grid {
            display: grid;
            grid-template-columns: 1fr; /* Uma coluna de cartões */
            gap: 25px;
        }

        .order-card {
            background: var(--surface-light);
            border-radius: 12px;
            box-shadow: 0 4px 15px var(--item-shadow);
            display: flex;
            flex-wrap: wrap; /* Permite quebrar linha em telas menores */
            gap: 20px;
            padding: 20px;
            border: 1px solid var(--border-light);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
            animation: cardAppear 0.5s ease-out forwards;
        }
        @keyframes cardAppear {
            to { opacity: 1; transform: translateY(0); }
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--item-hover-shadow);
        }

        .order-card-img { 
            width: 120px; 
            height: 120px; 
            border-radius: 8px; 
            object-fit: cover; 
            flex-shrink: 0; /* Impede que a imagem encolha */
        }
        .order-card-details { flex-grow: 1; min-width: 250px; /* Garante espaço mínimo para detalhes */ }
        .order-card-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 15px; }
        .order-card-title { font-size: 1.3rem; font-weight: 600; color: var(--text-dark); margin: 0; line-height: 1.2; }
        .order-card-id { font-size: 0.9rem; color: var(--text-medium); margin-top: 4px; }
        .order-card-info { font-size: 0.95rem; color: var(--text-medium); margin: 12px 0; }
        .order-card-info strong { color: var(--text-dark); }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            flex-shrink: 0; /* Impede que o badge encolha */
        }
        .status-badge.status-pending { background-color: var(--grey); }
        .status-badge.status-in-progress { background-color: var(--orange); }
        .status-badge.status-shipped { background-color: var(--blue); }
        .status-badge.status-planted { background-color: var(--accent); }

        /* Responsive Adjustments */
        @media (max-width: 600px) {
            .header-title { font-size: 2.2rem; }
            .order-card { flex-direction: column; } /* Empilha imagem e detalhes verticalmente */
            .order-card-img { width: 100%; height: 180px; } /* Imagem ocupa toda a largura */
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-container">
            <a href="inipage.php" class="logo">Treedom</a>
            </div>
    </nav>

    <div class="page-container">
        <h1 class="header-title">Meus Pedidos</h1>
        <div class="orders-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-card" style="animation-delay: <?= $result->current_field * 0.1 ?>s">
                    <img class="order-card-img" src="<?= htmlspecialchars($row['img'] ?? 'default-tree.jpg') ?>" alt="Imagem de <?= htmlspecialchars($row['especie']) ?>" />
                    <div class="order-card-details">
                        <div class="order-card-header">
                            <div>
                                <h2 class="order-card-title"><?= htmlspecialchars($row['nome']) ?></h2>
                                <p class="order-card-id">Pedido #<?= htmlspecialchars($row['id_pedido']) ?></p>
                            </div>
                            <span class="status-badge" data-status="<?= htmlspecialchars($row['status']) ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </div>
                        <div class="order-card-info">
                            <strong>Espécie:</strong> <?= htmlspecialchars($row['especie']) ?><br>
                            <strong>Local:</strong> <?= htmlspecialchars($row['localidade']) ?><br>
                            <strong>Data:</strong> <?= date("d/m/Y", strtotime($row['data_pedido'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if ($result->num_rows === 0): ?>
                <p style="text-align: center; padding: 20px; font-size: 1.1rem; color: var(--text-medium);">Você ainda não fez nenhum pedido.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>© <?php echo date("Y"); ?> Treedom. Cuidando do planeta, um pedido de cada vez.</p>
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Função para obter a classe de cor com base no texto do status
        function getStatusClass(statusText) {
            if (!statusText) return 'status-pending';
            const s = statusText.toLowerCase();
            if (s.includes('plantada')) return 'status-planted';
            if (s.includes('chegou') || s.includes('plantio')) return 'status-shipped';
            if (s.includes('andamento')) return 'status-in-progress';
            return 'status-pending'; // Status padrão
        }

        // Aplica a classe de cor correta a cada badge de status ao carregar a página
        document.querySelectorAll('.status-badge').forEach(badge => {
            const statusText = badge.dataset.status || badge.textContent;
            badge.classList.add(getStatusClass(statusText));
        });
    });
</script>

</body>
</html>