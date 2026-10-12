<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/ProductController.php';

// 從網址列取得商品 ID，預設為 1 (例如 index.php?id=1)
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$controller = new ProductController();
$controller->show($id);