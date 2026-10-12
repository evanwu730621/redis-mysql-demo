# PHP MVC Cache Demo (MySQL & Redis)

這是一個展示後端效能優化與 **Cache Aside ** 機制的簡單專案範例，採用自製的輕量級 PHP MVC 架構，並結合 Redis 記憶體快取來大幅降低資料庫負擔。

## 🚀 核心功能與特色

- **MVC 分層架構**：嚴格遵循 Controller 處理請求邏輯、Model 負責資料庫與快取存取的設計模式。
- **Cache Aside (快取機制)**：
  - **Cache Miss**：當快取未命中時，系統自動從 MySQL 查詢資料，並同步寫入 Redis。
  - **Cache Hit**：後續請求直接從 Redis 記憶體讀取，展現極致的效能提升。
- **互動式狀態介面**：結合前端動態狀態指示燈，可即時觀察資料來源是來自 MySQL 查詢還是 Redis 記憶體快取。

## 🛠️ 技術堆疊

- **Backend**: PHP (MVC Architecture)
- **Database**: MySQL
- **Cache**: Redis (透過 Docker 容器運行)
- **Frontend / UI**: Tailwind CSS (極簡美觀的現代化卡片設計)
- **Environment**: WAMP Server (Windows 11)

## 📂 專案結構

```text
redis-mysql-demo/
├── config/         # 資料庫與 Redis 連線設定
├── controllers/    # 核心控制器 (如 ProductController)
├── models/         # 資料模型與快取邏輯處理
├── vendor/         # Composer 套件依賴
├── composer.json   # 專案相依性管理
└── index.php       # 專案統一入口與路由分派
