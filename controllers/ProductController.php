<?php
require_once __DIR__ . '/../models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function show($id) {
        $result = $this->productModel->getProductById($id);

        if (!$result) {
            // 404 頁面 UI
            echo '<div class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
                    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full text-center border border-slate-100">
                        <div class="text-5xl mb-4">🔍</div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-2">404 - 找不到該商品</h2>
                        <p class="text-slate-500 mb-6">您尋找的商品可能已經下架或不存在。</p>
                        <a href="index.php?id=1" class="inline-block bg-indigo-600 text-white font-medium px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">返回首頁</a>
                    </div>
                  </div>';
            return;
        }

        $source = $result['source'];
        $product = $result['data'];

        // 判斷快取狀態以調整 Badge 樣式
        $isRedis = str_contains($source, 'Redis');
        $badgeBg = $isRedis ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200';
        $badgeDot = $isRedis ? 'bg-emerald-500' : 'bg-amber-500';

        // 輸出漂亮的 HTML / Tailwind UI
        echo '<!DOCTYPE html>
        <html lang="zh-Hant">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . htmlspecialchars($product['name']) . '</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-4">

            <!-- 頂部導覽或架構提示 -->
            <div class="w-full max-w-xl mb-4 flex justify-between items-center px-2">
                <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase">MVC Architecture Demo</span>
                <span class="text-xs text-slate-500 bg-white px-3 py-1 rounded-full shadow-sm border border-slate-200">後端效能展示</span>
            </div>

            <!-- 主卡片 -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 w-full max-w-xl overflow-hidden">
                
                <!-- 狀態橫幅 (Cache Status) -->
                <div class="px-6 py-3.5 border-b border-slate-100 flex items-center justify-between ' . $badgeBg . ' border-l-4">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-2.5 h-2.5 rounded-full ' . $badgeDot . ' animate-pulse"></span>
                        <span class="text-sm font-semibold tracking-wide">資料來源：' . htmlspecialchars($source) . '</span>
                    </div>
                </div>

                <!-- 內容區塊 -->
                <div class="p-8">
                    <!-- 商品名稱 -->
                    <h1 class="text-2xl font-bold text-slate-900 mb-4">' . htmlspecialchars($product['name']) . '</h1>

                    <!-- 價格與庫存方塊 -->
                    <div class="grid grid-cols-2 gap-4 my-6">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-400 block mb-1">商品價格</span>
                            <span class="text-2xl font-extrabold text-indigo-600">$' . number_format($product['price']) . '</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-400 block mb-1">目前庫存</span>
                            <span class="text-2xl font-extrabold text-slate-700">' . htmlspecialchars($product['stock']) . ' <span class="text-sm font-normal text-slate-400">件</span></span>
                        </div>
                    </div>

                    <!-- 互動按鈕區 -->
                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <button onclick="alert(\'加入購物車功能演示\')" class="flex-1 bg-indigo-600 text-white font-semibold py-3 px-6 rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center justify-center space-x-2">
                            <span>🛒 加入購物車</span>
                        </button>
                        <button onclick="location.reload();" class="bg-slate-100 text-slate-600 font-medium py-3 px-5 rounded-2xl hover:bg-slate-200 transition" title="重新整理看 Cache Hit 效果">
                            🔄 重新整理
                        </button>
                    </div>
                </div>
            </div>

            <!-- 底部小提示 -->
            <p class="text-xs text-slate-400 mt-6">💡 提示：連續點擊「重新整理」按鈕，狀態會從 MySQL 查詢瞬間變成 Redis 記憶體快取！</p>

        </body>
        </html>';
    }
}