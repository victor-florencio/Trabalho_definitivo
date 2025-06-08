<?php
include_once("code/conexao.php");
include_once("code/loginC.php"); 


if (!isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
$trees_newly_acquired = []; 
$show_payment_modal = false;
$payment_error = '';
$pedido_realizado = false;


$localidades = [];
$especies_por_localidade = [];
$resLoc = $conexao->query("SELECT DISTINCT localidade FROM arvores WHERE localidade IS NOT NULL AND localidade <> '' ORDER BY localidade ASC");
if ($resLoc) {
    while ($row = $resLoc->fetch_assoc()) {
        $localidades[] = $row['localidade'];
    }
}

$resEsp = $conexao->query("SELECT localidade, especie FROM arvores WHERE localidade IS NOT NULL AND localidade <> '' AND especie IS NOT NULL AND especie <> '' ORDER BY localidade, especie ASC");
if ($resEsp) {
    while ($row = $resEsp->fetch_assoc()) {
        $loc = $row['localidade'];
        $esp = $row['especie'];
        if (!isset($especies_por_localidade[$loc])) $especies_por_localidade[$loc] = [];
        if (!in_array($esp, $especies_por_localidade[$loc])) $especies_por_localidade[$loc][] = $esp;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmar_pagamento'])) {
        $kit = $_POST['kit'] ?? '';
        $localidade = trim($_POST['localidade'] ?? '');
        $especie = trim($_POST['especie'] ?? '');
        $pagamento = $_POST['pagamento'] ?? '';
        if ($localidade && $especie && $pagamento && ($kit == '2' || $kit == '5')) {
            // Insere na tabela pedidos
            $nome_pedido = $kit == '2' ? 'Kit Sementes Raras' : 'Kit Floresta Diversa';
            $status = 'Aguardando processamento';
            $img_pedido = 'NULL'; 
            $id_user_escaped = $conexao->real_escape_string($id_user);
            $nome_pedido_escaped = $conexao->real_escape_string($nome_pedido);
            $especie_escaped = $conexao->real_escape_string($especie);
            $localidade_escaped = $conexao->real_escape_string($localidade);
            $status_escaped = $conexao->real_escape_string($status);
            $img_escaped = $conexao->real_escape_string($img_pedido);
            $sql_pedido = "INSERT INTO pedidos (id_user, nome, especie, localidade, data_pedido, status, img)
                VALUES ('$id_user_escaped', '$nome_pedido_escaped', '$especie_escaped', '$localidade_escaped', NOW(), '$status_escaped', '$img_escaped')";
            $conexao->query($sql_pedido);
            
            $sql = $kit == '2'
                ? "SELECT id, nome, img, raridade FROM cards ORDER BY RAND() LIMIT 2"
                : "SELECT id, nome, img, raridade FROM cards ORDER BY RAND() LIMIT 5";
            $result = $conexao->query($sql);
            if ($result) {
                while ($linha = $result->fetch_assoc()) {
                    $trees_newly_acquired[] = $linha;
                }
                foreach ($trees_newly_acquired as $tree) {
                    $tree_id_escaped = $conexao->real_escape_string($tree['id']);
                    $sql_in = "INSERT INTO user_cards (id_user, id_card) VALUES ('$id_user_escaped', '$tree_id_escaped')";
                    $conexao->query($sql_in);
                }
            }
            $pedido_realizado = true;
        } else {
            $payment_error = "Preencha todos os campos para continuar.";
            $show_payment_modal = true;
        }
    }
   
    elseif (isset($_POST['ganhar']) || isset($_POST['ganhar5'])) {
        $show_payment_modal = true;
        $kit = isset($_POST['ganhar']) ? '2' : '5';
    }
}

$user_trees_collection = [];
$id_user_escaped_for_select = $conexao->real_escape_string($id_user);

$sql_todes = "SELECT c.id, c.nome, c.img, c.raridade FROM user_cards cc JOIN cards c ON cc.id_card = c.id WHERE cc.id_user = '$id_user_escaped_for_select' ORDER BY c.nome ASC";
$result_todes = $conexao->query($sql_todes);
if ($result_todes) {
    while ($linha = $result_todes->fetch_assoc()) {
        $user_trees_collection[] = $linha;
    }
} else {
    error_log("Error fetching user's tree collection: " . $conexao->error);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Bosque e Loja de Árvores | Treedom</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3a7d44;
            --secondary: #2d5a34;
            --accent: #5cb85c;
            --light: #f8f9fa;
            --dark: #343a40;
            --white: #ffffff;
            --card-bg: #ffffff;
            --card-shadow: rgba(0,0,0,0.08);
            --success: #28a745;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
        }

        /* Navigation */
        nav {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 10px 0;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li {
            margin-left: 25px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            padding: 10px 5px;
            display: flex; 
            align-items: center; 
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover,
        .nav-links a:focus {
            color: var(--accent);
        }

        .dropdown { position: relative; }
        .dropdown .seta {
          font-size: 0.7em; margin-left: 6px; line-height: 1;
          transition: transform 0.3s ease; display: inline-block;
        }
        .submenu {
          position: absolute; top: 100%; left: 0; background: var(--dark);
          border-radius: 6px; list-style: none; padding: 8px 0; margin: 8px 0 0 0;
          min-width: 180px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          opacity: 0; visibility: hidden; transform: translateY(10px);
          transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
          z-index: 10;
        }
        .submenu li a {
          display: block; padding: 10px 20px; color: var(--light);
          font-weight: 400; font-size: 0.95em; text-decoration: none;
          white-space: nowrap; transition: background-color 0.3s ease, color 0.3s ease;
        }
        .submenu li a:hover, .submenu li a:focus {
          background-color: var(--secondary); color: var(--white);
        }
        .dropdown:hover > .submenu, .dropdown:focus-within > .submenu {
          opacity: 1; visibility: visible; transform: translateY(0);
        }
        .dropdown:hover > a .seta, .dropdown:focus-within > a .seta {
          transform: rotate(180deg);
        }
        
        .page-title-container {
            text-align: center;
            padding: 30px 20px 10px;
        }
        .page-title-container h1 {
            font-size: 2.8rem;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2rem;
        }

        /* Store Item Section */
        .store-item-section {
            padding: 30px 20px;
            background: var(--white);
            margin-bottom: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px var(--card-shadow);
        }

        .store-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
            max-width: 700px; 
            margin: 0 auto; 
        }
        @media (min-width: 600px) {
            .store-item {
                flex-direction: row;
                text-align: left;
                gap: 35px;
                align-items: center;
                justify-content: flex-start; 
            }
        }

        .store-item-image {
            width: 100%;
            max-width: 220px;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
            display: block;
            margin-right: auto;
        }
        .store-item-image:hover {
            transform: scale(1.05);
        }

        .store-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center; 
        }
        @media (min-width: 600px) {
             .store-item-details {
                align-items: flex-start; 
            }
        }


        .store-item-details h3 { 
            font-size: 1.9rem; 
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 8px;
        }

        .store-item-description {
            font-size: 1rem;
            margin-bottom: 12px;
            color: var(--dark);
            max-width: 350px; 
            text-align: center;
        }
         @media (min-width: 600px) {
             .store-item-description {
                text-align: left;
            }
        }


        .store-item-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 20px;
        }
        .store-item-price span {
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--dark);
        }

        .btn {
            display: inline-block;
            background: var(--accent);
            color: var(--white) !important; 
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn:hover, .btn:focus {
            background: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .btn-buy { 
            background-color: var(--accent); 
            padding: 14px 35px;
            font-size: 1.1rem;
            width: 100%; 
            max-width: 250px; 
        }
        .btn-buy:hover, .btn-buy:focus {
            background-color: var(--primary); 
            transform: translateY(-2px) scale(1.02);
        }

        /* Reveal Section for New Items */
        .reveal-section {
            padding: 30px 20px 40px;
            margin-bottom: 40px;
            background-color: var(--white); 
            border-radius: 12px;
            box-shadow: 0 8px 25px var(--card-shadow);
            text-align: center;
        }

        .reveal-section .congrats-title {
            font-size: 2.8rem;
            color: var(--success); 
            margin-bottom: 10px;
            animation: fadeInDrop 0.5s ease-out forwards;
        }

        .reveal-section .reveal-subtitle {
            font-size: 1.2rem;
            color: var(--dark);
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease-out 0.2s forwards; 
            opacity: 0; 
        }
        
        @keyframes fadeInDrop {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Item display styles */
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
            gap: 20px; 
        }
        
        .item-grid.centered-grid {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: nowrap; 
        }
        .item-grid.centered-grid .tree-display-item {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
        }
        
        .tree-display-item {
            background: var(--card-bg);
            border-radius: 10px; 
            overflow: hidden;
            box-shadow: 0 4px 12px var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .tree-display-item:hover {
            transform: translateY(-6px) scale(1.03); 
            box-shadow: 0 7px 20px rgba(0,0,0,0.12);
        }
        
        .tree-display-item img {
            width: 100%;
            height: auto;
            aspect-ratio: 3/4.2; 
            object-fit: contain;
            background: #f8f9fa;
            border-bottom: 1px solid #eee; 
            cursor: pointer;
        }
        
        .tree-display-item-content {
            padding: 12px; 
            text-align: center;
            flex-grow: 1; 
        }
        
        .tree-display-item-content h3 {
            margin-top: 0;
            margin-bottom: 0; 
            font-size: 1rem; 
            font-family: 'Montserrat', sans-serif; 
            font-weight: 600;
        }
        .raridade-comum { color: #3a7d44; }
        .raridade-rara { color: #2196f3; }
        .raridade-epica { color: #a259e6; }
        .raridade-lendaria { 
            color: #ffd700;
            text-shadow: 0 0 6px #fffbe6, 0 0 12px #ffd70099;
        }

        .revealed-item {
            opacity: 0; 
        }

        @keyframes revealItemAnimation {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.7) rotateY(-90deg);
            }
            60% {
                 transform: translateY(-10px) scale(1.05) rotateY(10deg);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1) rotateY(0deg);
            }
        }

        /* Footer */
        footer {
            background: var(--dark); color: var(--light);
            padding: 30px 20px; text-align: center; margin-top: 240px;
        }
        footer p { margin:0; font-size: 0.9rem; }

        /* Scroll animation (general) */
        .scroll-animate {
            opacity: 0;
            transform: translateY(25px); 
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title-container h1 { font-size: 2.2rem; }
            .nav-container { flex-direction: column; align-items: center; }
            .nav-links { margin-top: 15px; }
            .nav-links li { margin: 0 8px; }
            .section-title { font-size: 1.8rem; }
            .store-item-section .section-title { font-size: 2rem; } 
            .item-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px;}
        }
        @media (max-width: 480px) {
            .nav-links { flex-direction: column; align-items: center; width: 100%;}
            .nav-links li { margin: 8px 0; width: 100%; text-align: center;}
            .nav-links li a { display: block; padding: 8px; width:100%; justify-content: center;}
            .dropdown { width: 100%; }
            .dropdown > a { justify-content: center; }
            .submenu { width: 100%; box-sizing: border-box;}
            .item-grid { grid-template-columns: 1fr 1fr; gap: 12px;}
            .store-item-details h3 { font-size: 1.6rem; }
            .store-item-price { font-size: 1.1rem; }
            .btn-buy { padding: 12px 25px; font-size: 1rem;}
            .reveal-section .congrats-title { font-size: 2.2rem; }
            .store-item-image {
                 margin-left: auto;
                 margin-right: auto;
                 margin-top: 0;
            }
        }

        .store-items-row {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        @media (min-width: 600px) {
            .store-items-row {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }
        }

        /* Modal para imagem ampliada */
        .modal-img-viewer {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.85);
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s;
        }
        .modal-img-viewer.active {
            display: flex;
        }
        .modal-img-viewer img {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            background: #fff;
        }
        .modal-img-viewer .close-modal {
            position: absolute;
            top: 30px;
            right: 40px;
            font-size: 2.5rem;
            color: #fff;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 10001;
            font-weight: bold;
            transition: color 0.2s;
        }
        .modal-img-viewer .close-modal:hover {
            color: var(--accent);
        }

        /* Modal de pagamento */
        .modal-pagamento-bg {
            display: none;
            position: fixed;
            z-index: 9998;
            left: 0; top: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.65);
            justify-content: center;
            align-items: center;
        }
        .modal-pagamento-bg.active {
            display: flex;
        }
        .modal-pagamento {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            padding: 32px 28px 24px;
            max-width: 370px;
            width: 95vw;
            position: relative;
            text-align: left;
            animation: fadeInDrop 0.4s;
            overflow: hidden; 
            transition: height 0.4s ease;
        }
        .modal-pagamento h2 {
            margin-top: 0;
            font-size: 1.5rem;
            color: var(--primary);
        }
        .modal-pagamento label {
            font-weight: 600;
            margin-top: 12px;
            display: block;
        }
        .modal-pagamento input[type="text"] {
            width: 100%;
            padding: 10px 3px; /* aumentado */
            margin-top: 4px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1.15rem; /* aumentado */
        }
        .modal-pagamento .btn {
            width: 100%;
            margin-top: 10px;
        }
        .modal-pagamento .btn-buy {
            display: block;
            margin: 24px auto 0 auto;
            width: 80%;
            max-width: 260px;
            text-align: center;
        }
        .modal-pagamento .close-modal-pagamento {
            position: absolute;
            top: 12px;
            right: 18px;
            font-size: 1.7rem;
            color: #888;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 10001;
            font-weight: bold;
        }
        .modal-pagamento .close-modal-pagamento:hover {
            color: var(--accent);
        }
        .modal-pagamento .erro-pagamento {
            color: #c00;
            font-size: 0.98em;
            margin-bottom: 8px;
        }
        
        .metodos-pagamento {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            margin-top: 4px;
            margin-bottom: 12px;
        }
        @media (min-width: 350px) {
             .metodos-pagamento {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }

        .metodo-pagamento-btn {
            background-color: #f0f0f0;
            border: 2px solid #ddd;
            padding: 10px 5px;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-weight: 600;
            font-size: 0.9em;
            color: #555;
            transition: all 0.2s ease-in-out;
        }
        .metodo-pagamento-btn:hover {
            background-color: #e9e9e9;
            border-color: #ccc;
        }
        .metodo-pagamento-btn.active {
            background-color: #e0f2e3;
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 5px rgba(58, 125, 68, 0.3);
        }

        #selecao-inicial-pagamento, #form-cartao-credito {
            transition: opacity 0.4s ease, transform 0.4s ease;
        }
        
        .btn-voltar {
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            padding: 8px 0;
            margin-top: 10px;
        }
        .btn-voltar:hover {
            text-decoration: underline;
        }

        /* --- ESTILOS PARA MELHORAR OS SELECTS --- */
        .select-container {
            position: relative;
            width: 100%;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        .select-container::after {
            content: '▼';
            font-size: 0.8rem;
            color: var(--primary);
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none; /* Permite clicar no select através da seta */
            transition: color 0.3s ease;
        }

        .select-container select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            
            width: 100%;
            padding: 10px 35px 10px 12px; /* Espaço para a nova seta */
            border-radius: 6px;
            border: 1px solid #ccc;
            background-color: var(--white);
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            color: var(--dark);
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .select-container select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(58, 125, 68, 0.2);
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="inipage.php" class="logo">Treedom</a>
            <ul class="nav-links">
                <li><a href="pedidos.php">Minhas Compras</a></li>
                <li><a href="inipage.php#about">Sobre</a></li>
                <li><a href="loja.php">Loja de Árvores</a></li>
                <li class="dropdown">
                    <?php if(isset($_SESSION['nome'])): ?>
                        <a href="#"><?php echo htmlspecialchars($_SESSION['nome']);?> <span class="seta">&#9660;</span></a>
                        <ul class="submenu">
                            <li><a href="loja.php">Minhas Árvores</a></li> 
                            <li><a href="inventario.php">Inventário</a></li> 
                            <li><a href="code/logout.php">Sair</a></li>
                        </ul>
                    <?php else: ?>
                        <a href="#">Login/Cadastro <span class="seta">&#9660;</span></a>
                        <ul class="submenu">
                            <li><a href="login.php">Login</a></li>
                            <li><a href="cadastro.php">Cadastro</a></li>
                        </ul>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="page-title-container scroll-animate">
        <h1>Loja de Árvores</h1>
    </div>
    
    <div class="container">
        <section class="store-item-section scroll-animate">
           
            <div class="store-items-row">
                <div class="store-item">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQPSnpzOOJ_IIWn5ulOjQRCCZnd1S8b7ri4sQ&s" alt="Kit de Plantio de Árvores" class="store-item-image">
                    <div class="store-item-details">
                        <h3>Sementes Raras</h3>
                        <p class="store-item-description">Compre uma árvore e ganhe 2 cartas para seu bosque</p>
                        <p class="store-item-price">Preço: <span>R$20</span></p>
                        <form action="loja.php#newly-acquired" method="post" class="form-kit">
                            <button type="submit" name="ganhar" class="btn btn-buy">Adquirir árvore</button>
                        </form>
                    </div>
                </div>
                <div class="store-item">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" alt="Kit Floresta Diversa" class="store-item-image">
                    <div class="store-item-details">
                        <h3>Floresta Diversa</h3>
                        <p class="store-item-description">Receba 5 cartas diferentes para enriquecer ainda mais seu bosque!</p>
                        <p class="store-item-price">Preço: <span>R$50</span></p>
                        <form action="loja.php#newly-acquired" method="post" class="form-kit">
                            <button type="submit" name="ganhar5" class="btn btn-buy">Adquirir Floresta</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal-pagamento-bg<?php if($show_payment_modal) echo ' active'; ?>" id="modalPagamentoBg">
            <div class="modal-pagamento" id="modalPagamento">
                <button class="close-modal-pagamento" id="closeModalPagamentoBtn" aria-label="Fechar">&times;</button>
                
                <form action="loja.php#newly-acquired" method="post" id="formPagamento">
                    <div id="selecao-inicial-pagamento">
                        <h2>Escolha onde plantar</h2>
                        <?php if($payment_error): ?>
                            <div class="erro-pagamento"><?php echo htmlspecialchars($payment_error); ?></div>
                        <?php endif; ?>
                        
                        <input type="hidden" name="kit" value="<?php echo htmlspecialchars($kit ?? ($_POST['kit'] ?? '2')); ?>">
                        
                        <label for="localidade">Localidade:</label>
                        <div class="select-container">
                            <select name="localidade" id="localidade" required>
                                <option value="">Selecione</option>
                                <?php foreach($localidades as $loc): ?>
                                    <option value="<?php echo htmlspecialchars($loc); ?>" <?php if(isset($_POST['localidade']) && $_POST['localidade'] == $loc) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($loc); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label for="especie">Espécie:</label>
                        <div class="select-container">
                            <select name="especie" id="especie" required>
                                <option value="">Selecione</option>
                                <?php
                                    $especie_selected = $_POST['especie'] ?? '';
                                    $localidade_selected = $_POST['localidade'] ?? '';
                                    if ($localidade_selected && isset($especies_por_localidade[$localidade_selected])) {
                                        foreach($especies_por_localidade[$localidade_selected] as $esp) {
                                            $sel = ($especie_selected == $esp) ? 'selected' : '';
                                            echo '<option value="'.htmlspecialchars($esp).'" '.$sel.'>'.htmlspecialchars($esp).'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <label>Forma de pagamento:</label>
                        <div class="metodos-pagamento">
                            <button type="button" class="metodo-pagamento-btn" data-metodo="cartao">Cartão de Crédito</button>
                            <button type="button" class="metodo-pagamento-btn" data-metodo="pix">Pix</button>
                            <button type="button" class="metodo-pagamento-btn" data-metodo="boleto">Boleto</button>
                        </div>
                        <input type="hidden" name="pagamento" id="pagamento_hidden" value="">
                    </div>

                    <div id="form-cartao-credito" style="display: none; opacity: 0; transform: translateX(20px);">
                        <h2>Dados do Cartão</h2>
                        <label for="cartao_numero">Número do Cartão:</label>
                        <input type="text" id="cartao_numero" placeholder="0000 0000 0000 0000">

                        <label for="cartao_nome">Nome no Cartão:</label>
                        <input type="text" id="cartao_nome" placeholder="Nome como impresso no cartão">

                        <div style="display: flex; gap: 10px;">
                            <div style="flex-grow: 1;">
                                <label for="cartao_validade">Validade:</label>
                                <input type="text" id="cartao_validade" placeholder="MM/AA">
                            </div>
                            <div style="width: 80px;">
                                <label for="cartao_cvv">CVV:</label>
                                <input type="text" id="cartao_cvv" placeholder="123">
                            </div>
                        </div>
                        <button type="button" class="btn-voltar" id="btnVoltarPagamento">← Voltar</button>
                    </div>
                    
                    <button type="submit" name="confirmar_pagamento" class="btn btn-buy">Confirmar Compra</button>
                </form>
            </div>
        </div>

        <?php if ($pedido_realizado && !empty($trees_newly_acquired)): ?>
        <section id="newly-acquired" class="reveal-section"> 
            <h2 class="congrats-title">Parabéns!</h2>
            <p class="reveal-subtitle">Você cultivou novas árvores:</p>
            <div class="item-grid centered-grid">
                <?php foreach($trees_newly_acquired as $index => $tree): ?>
                    <?php
                        $raridade = isset($tree['raridade']) ? strtolower($tree['raridade']) : 'comum';
                        $classeRaridade = 'raridade-' . $raridade;
                    ?>
                    <div class="tree-display-item revealed-item" style="animation: revealItemAnimation 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94) <?php echo $index * 0.25; ?>s forwards;">
                        <img src="<?php echo htmlspecialchars($tree['img']); ?>" alt="<?php echo htmlspecialchars($tree['nome']); ?>" class="tree-img-clickable">
                        <div class="tree-display-item-content">
                            <h3 class="<?php echo $classeRaridade; ?>"><?php echo htmlspecialchars($tree['nome']); ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <hr style="margin: 40px auto; border: 0; border-top: 1px solid #ddd; width: 80%;" class="scroll-animate">
        <?php elseif ($pedido_realizado && empty($trees_newly_acquired)): ?>
        <section id="no-new-items" class="reveal-section scroll-animate">
            <h2 class="section-title" style="color: var(--primary);">Oops!</h2>
             <p style="text-align:center; font-size: 1.1rem;">Não foi possível cultivar novas árvores desta vez. Parece que o kit não germinou. Tente novamente!</p>
        </section>
        <hr style="margin: 40px auto; border: 0; border-top: 1px solid #ddd; width: 80%;" class="scroll-animate">
        <?php endif; ?>
    </div>
    
    <footer class="scroll-animate">
        <p>© <?php echo date("Y"); ?> Treedom. Todos os direitos reservados.</p>
    </footer>

    <div class="modal-img-viewer" id="modalImgViewer">
        <button class="close-modal" id="closeModalBtn" aria-label="Fechar imagem ampliada">&times;</button>
        <img src="" alt="Árvore ampliada" id="modalImgTag">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const animatedElements = document.querySelectorAll('.scroll-animate');

            if ("IntersectionObserver" in window) {
                const observer = new IntersectionObserver((entries, observerInstance) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observerInstance.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                animatedElements.forEach(el => {
                    observer.observe(el);
                });
            } else {
                animatedElements.forEach(el => {
                    el.classList.add('visible');
                });
            }

            if (window.location.hash === '#newly-acquired' && document.getElementById('newly-acquired')) {
                setTimeout(() => { 
                    document.getElementById('newly-acquired').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start' 
                    });
                }, 100); 
            }

            // Modal de imagem ampliada
            const modal = document.getElementById('modalImgViewer');
            const modalImg = document.getElementById('modalImgTag');
            const closeModalBtn = document.getElementById('closeModalBtn');
            document.querySelectorAll('.tree-img-clickable').forEach(img => {
                img.addEventListener('click', function() {
                    modalImg.src = this.src;
                    modalImg.alt = this.alt;
                    modal.classList.add('active');
                });
            });
            if(closeModalBtn) {
                closeModalBtn.addEventListener('click', () => {
                    modal.classList.remove('active');
                    modalImg.src = '';
                });
            }
            if(modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                        modalImg.src = '';
                    }
                });
            }
            document.addEventListener('keydown', (e) => {
                if (modal && modal.classList.contains('active') && (e.key === 'Escape' || e.key === 'Esc')) {
                    modal.classList.remove('active');
                    modalImg.src = '';
                }
            });

            // Modal de pagamento
            const modalPagamentoBg = document.getElementById('modalPagamentoBg');
            const closeModalPagamentoBtn = document.getElementById('closeModalPagamentoBtn');
            if (closeModalPagamentoBtn) {
                closeModalPagamentoBtn.addEventListener('click', () => {
                    modalPagamentoBg.classList.remove('active');
                    window.location.href = 'loja.php';
                });
            }
            if (modalPagamentoBg) {
                modalPagamentoBg.addEventListener('click', (e) => {
                    if (e.target === modalPagamentoBg) {
                        modalPagamentoBg.classList.remove('active');
                        window.location.href = 'loja.php';
                    }
                });
            }
            document.addEventListener('keydown', (e) => {
                if (modalPagamentoBg && modalPagamentoBg.classList.contains('active') && (e.key === 'Escape' || e.key === 'Esc')) {
                    modalPagamentoBg.classList.remove('active');
                    window.location.href = 'loja.php';
                }
            });

            // Espécies por localidade vindas do PHP
            const especiesPorLocalidade = <?php echo json_encode($especies_por_localidade); ?>;
            const selectLocalidade = document.getElementById('localidade');
            const selectEspecie = document.getElementById('especie');

            if (selectLocalidade && selectEspecie) {
                selectLocalidade.addEventListener('change', function() {
                    const loc = this.value;
                    selectEspecie.innerHTML = '<option value="">Selecione</option>';
                    if (loc && especiesPorLocalidade[loc]) {
                        especiesPorLocalidade[loc].forEach(function(esp) {
                            const opt = document.createElement('option');
                            opt.value = esp;
                            opt.textContent = esp;
                            selectEspecie.appendChild(opt);
                        });
                    }
                });
            }

            // --- INÍCIO DO NOVO CÓDIGO PARA O MODAL DE PAGAMENTO ---
            const selecaoInicialPanel = document.getElementById('selecao-inicial-pagamento');
            const cartaoCreditoPanel = document.getElementById('form-cartao-credito');
            const metodoPagamentoBtns = document.querySelectorAll('.metodo-pagamento-btn');
            const pagamentoHiddenInput = document.getElementById('pagamento_hidden');
            const btnVoltar = document.getElementById('btnVoltarPagamento');

            metodoPagamentoBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    metodoPagamentoBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const metodo = this.dataset.metodo;
                    pagamentoHiddenInput.value = metodo;

                    if (metodo === 'cartao') {
                        selecaoInicialPanel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        cartaoCreditoPanel.style.transition = 'opacity 0.3s ease 0.1s, transform 0.3s ease 0.1s';
                        
                        selecaoInicialPanel.style.opacity = '0';
                        selecaoInicialPanel.style.transform = 'translateX(-20px)';

                        setTimeout(() => {
                           selecaoInicialPanel.style.display = 'none';
                           cartaoCreditoPanel.style.display = 'block';
                           setTimeout(() => {
                               cartaoCreditoPanel.style.opacity = '1';
                               cartaoCreditoPanel.style.transform = 'translateX(0)';
                           }, 20);
                        }, 300);
                    }
                });
            });

            if (btnVoltar) {
                btnVoltar.addEventListener('click', function() {
                     metodoPagamentoBtns.forEach(b => b.classList.remove('active'));
                     pagamentoHiddenInput.value = '';

                    cartaoCreditoPanel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    selecaoInicialPanel.style.transition = 'opacity 0.3s ease 0.1s, transform 0.3s ease 0.1s';

                    cartaoCreditoPanel.style.opacity = '0';
                    cartaoCreditoPanel.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        cartaoCreditoPanel.style.display = 'none';
                        selecaoInicialPanel.style.display = 'block';
                        setTimeout(() => {
                            selecaoInicialPanel.style.opacity = '1';
                            selecaoInicialPanel.style.transform = 'translateX(0)';
                        }, 20);
                    }, 300);
                });
            }
            // --- FIM DO NOVO CÓDIGO ---

            // --- INÍCIO DA FORMATAÇÃO DOS CAMPOS DO CARTÃO ---
            const cartaoNumero = document.getElementById('cartao_numero');
            const cartaoValidade = document.getElementById('cartao_validade');
            const cartaoCVV = document.getElementById('cartao_cvv');

            if (cartaoNumero) {
                cartaoNumero.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '').slice(0,16);
                    value = value.replace(/(.{4})/g, '$1 ').trim();
                    this.value = value;
                });
            }
            if (cartaoValidade) {
                cartaoValidade.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '').slice(0,4);
                    if (value.length > 2) {
                        value = value.replace(/(\d{2})(\d{1,2})/, '$1/$2');
                    }
                    this.value = value;
                });
            }
            if (cartaoCVV) {
                cartaoCVV.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '').slice(0,4);
                    this.value = value;
                });
            }
           
        });
    </script>
</body>
</html>