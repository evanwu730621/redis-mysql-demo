<?php
require_once __DIR__ . '/../config/Database.php';

class ProductModel {
    private $db;
    private $redis;

    public function __construct() {
        // 1. 連線 MySQL
        $this->db = Database::connect();

        // 2. 連線 Docker 裡的 Redis
        $this->redis = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }

    public function getProductById($id) {
        $cacheKey = "product:{$id}";

        // --- 步驟 1：先檢查 Redis 裡有沒有快取 ---
        $cachedData = $this->redis->get($cacheKey);
        if ($cachedData) {
            // Cache Hit：直接解序列化回傳（省去查 DB）
            return ['source' => '🚀 Redis 記憶體快取 (Cache Hit)', 'data' => json_decode($cachedData, true)];
        }

        // --- 步驟 2：如果沒有，向 MySQL 查詢 ---
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            return null;
        }

        // --- 步驟 3：寫入 Redis 快取，設定 60 秒過期 (TTL) ---
        $this->redis->setex($cacheKey, 60, json_encode($product));

        return ['source' => '💾 MySQL 資料庫查詢 (Cache Miss - 已寫入快取)', 'data' => $product];
    }
}