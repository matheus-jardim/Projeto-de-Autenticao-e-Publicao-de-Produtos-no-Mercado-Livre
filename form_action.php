<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
    $category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS));
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    
    if (!$title || !$category || !$price || !$stock) {
        echo "Por favor, preencha todos os campos obrigatórios.";        
        exit;
    }
    
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($price, ENT_QUOTES, 'UTF-8');
    $stock = htmlspecialchars($stock, ENT_QUOTES, 'UTF-8');

    if ($title && $category && $price !== false && $stock !== false) {
        $accessToken = $_SESSION['access_token'];        
        $siteId = 'MLB';

        $productData = [
            'title' => $title,
            'category_id' => $category,
            'price' => $price,
            'available_quantity' => $stock,
            'listing_type_id' => 'gold_special',
            "currency_id" => "BRL",
            "buying_mode" => "buy_it_now",
            "condition" => "new",
            "sale_terms" => [
                [
                    "id" => "WARRANTY_TYPE",
                    "value_name" => "Garantía do vendedor"
                ],
                [
                    "id" => "WARRANTY_TIME",
                    "value_name" => "0 dias"
                ]
            ],
            "pictures" => [
                [
                    "source" => "http://mla-s2-p.mlstatic.com/968521-MLA20805195516_072016-O.jpg"
                ]
            ],
            "attributes" => [
                [
                    "id" => "BRAND",
                    "value_name" => "Marca do producto"
                ],
                [
                    "id" => "EAN",
                    "value_name" => "7898095297749"
                ],
                [
                    'id' => 'MODEL',
                    'value_name' => 'Valor do Modelo'
                ],
                [
                    'id' => 'COLOR',
                    'value_name' => 'Valor da Cor',
                ],
            ]
        ];

        $url = "https://api.mercadolibre.com/items?access_token=$accessToken&site_id=$siteId";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($productData));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $responseData = json_decode($response, true);

            if (isset($responseData['id'])) {
                $productId = $responseData['id'];
                echo "Produto publicado com sucesso - ID do produto publicado: $productId";
            } else {
                echo "Erro ao publicar o produto, é necessário mais especificações dos atributos do item.";
            }
        } else {
            echo 'Erro na chamada à API do Mercado Livre.';
        }
    } else {
        echo "Dados inválidos do formulário.";
    }
}