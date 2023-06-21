<?php
session_start();
require_once('./config.php');
require_once('./form_category.php');

if (empty($_SESSION['access_token'])) {
    header("Location: $base/auth/mercadolivre/auth.php");
    exit;
}
if ((time() + 300) >= $_SESSION['expire']) {
    header("Location: $base/auth/mercadolivre/callback.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h1>Publicar Produto</h1>

    <div id="form_status"></div>
    <form id="productForm">
        <label for="title">Título: <em>Produto + Marca + modelo do produto + algumas especificações que ajudem a identificar o produto.</em></label><br>
        <input type="text" id="title" name="title" placeholder="Informe o Título" required><br><br>

        <label for="category">Categoria: <em>Selecione as opções após preencher o título</em></label><br>
        <select name="category" id="category" required>
            <option value="TITULO" disabled selected>PREENCHA O TÍTULO PRIMEIRO</option>
        </select><br><br>

        <label for="price">Preço:</label><br>
        <input type="number" step="0.01" id="price" name="price" placeholder="Informe o Preço: R$" required><br><br>

        <label for="stock">Estoque:</label><br>
        <input type="number" id="stock" name="stock" placeholder="Informe o Estoque" required><br><br>

        <input type="submit" id="form-submit" value="Publicar Produto">
    </form>
    <div id="form_info"></div>
    <button id="new_form">Publicar mais um produto</button>

    <script src="assets/js/script.js"></script>
</body>

</html>