
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="cus.css">
</head>
<body>
    <header>
    <nav>
        <div class="nav-container">
            <a href="inipage.php" class="logo">Treedom</a>
            
        </div>
    </nav>
    </header>
    <section class = "card9">
            <h1>tu</h1>
    </section>
</body>
</html>

<?php

include("invcode.php");
include_once("code/loginC.php");


$usuario = $_SESSION['email'];

if (isset($_SESSION['usuarios_cards'][$usuario])) {
    echo "<div class='card-container'>";
    foreach ($_SESSION['usuarios_cards'][$usuario] as $card) {
        echo"
                <a class='card'>
                    <img src='{$card['imagem']}' alt='' class='card-image'>
                    <div class='card-content'>
                        <h3 class='card-title'>{$card['nome']}</h3>
                    </div>
                </a>
            ";
    }
    echo "</div>"; 
} else {
    echo "Nenhum card encontrado.";
}
?>

<style>
.card-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); 
    gap: 20px;
    padding: 20px;
    max-width: 1280px;
    margin: 0 auto;
    box-sizing: border-box;
}

.card-container a {
    text-decoration: none;
    color: inherit;
}

.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.card::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: var(--primary-color);
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.3s ease;
}

.card:hover::after {
    transform: scaleX(1);
    transform-origin: left;
}

.card-image {
    width: 80%;
    height: 200px;
    object-fit: cover;
    margin-top: 20px;
}

.card-content {
    padding: 15px;
    text-align: center;
}

.card-title {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: var(--primary-color);
    font-weight: 700;
}

.card-description {
    color: var(--text-light);
    font-size: 0.9rem;
    line-height: 1.5;
}


@media (max-width: 1024px) {
  .card-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .card-container {
    grid-template-columns: 1fr;
  }
}

</style>