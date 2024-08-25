<?php
require 'ProductScraper.php';

$query = $_GET['url'] ?? '';
$productArray = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($query)) {
        $error = 'Required.';
    } elseif (!filter_var($query, FILTER_VALIDATE_URL)) {
        $error = 'Please enter a valid URL.';
    } else {
        // Filter products based on the query URL
        $productArray = new ProductScraper($query);
        $productArray = $productArray->fetchContent();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
</head>
<body>
<div class="container my-5">
    <h4 class="text-center my-4">DEFACTO.Scraper:
        <a href="<?= $query; ?>" target="_blank">
            <?= $query; ?>
        </a>
    </h4>
    <form action="index.php" method="GET" class="w-50 form-group mx-auto my-4">
        <input type="text" class="form-control" id="url" name="url" placeholder="URL Daxil et..."
               value="<?= $query; ?>"/>
        <span class="text-danger"><?= $error; ?></span>
    </form>
    <div class="row justify-content-center gap-2">
        <?php

        if (!is_null($productArray)):
            foreach ($productArray as $product) {
                echo '<div class="card" style="width: 18rem;">';
                echo '<img src="//' . $product['image'] . '" class="card-img-top" alt="...">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $product['title'] . '</h5>';
                echo '<p class="card-text">' . $product['title'] . '</p >';
                echo '<a href = "#" class="btn btn-outline-primary" >' . $product['price'] . '</a > ';
                echo '</div > ';
                echo '</div > ';
            }
        else:
            echo '<h4 class="text-center text-warning my-4">No products found.</h4>';
        endif
        ?>
    </div>
</div>
</body>
</html>
