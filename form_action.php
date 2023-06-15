<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI' === '/publish-product' ]) {
    $requestData = json_decode(file_get_contents('php://input',true));
    if($requestData) {
        if($title && $category && $price && $stock) {
            $title = filter_var($requestData['title'], FILTER_SANITIZE_SPECIAL_CHARS);
            $category = filter_var($requestData['category'], FILTER_SANITIZE_SPECIAL_CHARS);
            $price = filter_var($requestData['price'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($requestData['title'], FILTER_VALIDATE_INT);

            echo "$title && $category && $price && $stock";
        }
    }
    


}