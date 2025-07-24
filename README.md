# ToDo List 專案管理系統

這是一個基於 Laravel 和 Filament 的專案管理系統，提供完整的專案和待辦事項管理功能。

## 🚀 功能特色

### 📋 專案管理
- **專案建立與編輯**：完整的專案資訊管理
- **專案狀態追蹤**：規劃中、進行中、已完成、暫停、已取消
- **專案時間管理**：開始日期和結束日期設定
- **專案負責人**：指派專案負責人

### ✅ 待辦事項管理
- **待辦事項建立**：詳細的待辦事項資訊
- **優先級管理**：高、中、低優先級設定
- **狀態追蹤**：待處理、進行中、已完成、已取消
- **截止日期**：時間管理功能
- **專案歸屬**：每個待辦事項都屬於特定專案

### 👥 使用者管理
- **使用者帳號**：完整的使用者管理系統
- **權限控制**：基於 Filament 的權限管理
- **負責人指派**：專案和待辦事項的負責人指派

### 📊 儀表板功能
- **統計概覽**：專案和待辦事項的統計資訊
- **最近待辦事項**：顯示最新的待辦事項
- **狀態追蹤**：即時查看各項狀態

## 🛠 技術架構

### 後端技術
- **Laravel 12**：PHP 框架
- **Filament 3**：管理介面框架
- **SQLite**：資料庫（可切換至 MySQL/PostgreSQL）
- **Eloquent ORM**：資料庫操作

### 前端技術
- **Filament UI**：現代化的管理介面
- **TinyMCE**：富文字編輯器
- **Flatpickr**：日期時間選擇器
- **Alpine.js**：互動式功能

### 套件依賴
- `filament/filament`：管理介面框架
- `intervention/image`：圖片處理
- `coolsam/flatpickr`：日期選擇器
- `mohamedsabil83/filament-forms-tinyeditor`：富文字編輯器

## 📁 專案結構

```
todo-list/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── ProjectResource/     # 專案管理
│   │   │   └── TodoResource/        # 待辦事項管理
│   │   └── Widgets/                 # 儀表板元件
│   ├── Models/
│   │   ├── Project.php             # 專案模型
│   │   ├── Todo.php                # 待辦事項模型
│   │   └── User.php                # 使用者模型
│   └── ...
├── database/
│   ├── migrations/                 # 資料庫遷移
│   ├── factories/                  # 測試資料工廠
│   └── seeders/                    # 資料填充器
└── ...
```

## 🚀 安裝與設定

### 1. 環境需求
- PHP 8.2+
- Composer
- Node.js (可選，用於前端資源編譯)

### 2. 安裝步驟

```bash
# 1. 複製專案
git clone [repository-url]
cd todo-list

# 2. 安裝依賴
composer install

# 3. 環境設定
cp .env.example .env
php artisan key:generate

# 4. 資料庫設定（使用 SQLite）
touch database/database.sqlite
# 或修改 .env 檔案設定 MySQL/PostgreSQL

# 5. 執行遷移和填充資料
php artisan migrate:fresh --seed

# 6. 建立管理員帳號
php artisan make:filament-user

# 7. 啟動開發伺服器
php artisan serve
```

### 3. 管理介面存取
- 網址：`http://localhost:8000/admin`
- 使用建立的管理員帳號登入

## 📊 資料庫結構

### Projects 表
- `id`：主鍵
- `name`：專案名稱
- `description`：專案描述
- `status`：專案狀態
- `start_date`：開始日期
- `end_date`：結束日期
- `user_id`：負責人 ID
- `created_at`：建立時間
- `updated_at`：更新時間

### Todos 表
- `id`：主鍵
- `title`：標題
- `description`：描述
- `due_date`：截止日期
- `priority`：優先級
- `status`：狀態
- `user_id`：負責人 ID
- `project_id`：專案 ID
- `created_at`：建立時間
- `updated_at`：更新時間

### Users 表
- `id`：主鍵
- `name`：姓名
- `email`：電子郵件
- `password`：密碼
- `created_at`：建立時間
- `updated_at`：更新時間

## 🔧 功能說明

### 專案管理
1. **建立專案**：填寫專案名稱、描述、狀態、時間和負責人
2. **編輯專案**：修改專案資訊
3. **專案列表**：查看所有專案，支援搜尋和篩選
4. **專案待辦事項**：在專案頁面直接管理該專案的待辦事項

### 待辦事項管理
1. **建立待辦事項**：選擇專案，填寫標題、描述、優先級等
2. **編輯待辦事項**：修改待辦事項資訊
3. **待辦事項列表**：查看所有待辦事項，支援多種篩選條件
4. **狀態管理**：直接在列表中更改狀態

### 儀表板
1. **統計概覽**：顯示專案和待辦事項的統計資訊
2. **最近待辦事項**：顯示最新的 5 筆待辦事項
3. **快速操作**：快速存取各項功能

## 🎨 自訂功能

### 新增狀態
如需新增專案或待辦事項狀態，請修改：
1. 資料庫遷移檔案中的 enum 定義
2. 模型中的 fillable 和 casts 設定
3. Filament Resource 中的選項設定

### 新增欄位
如需新增欄位，請：
1. 建立新的遷移檔案
2. 更新模型設定
3. 修改 Filament Resource 的表單和表格

## 📝 開發筆記

### 遵循的規則
- 使用繁體中文介面
- 遵循 Laravel 最佳實踐
- 使用 Filament 3 的新功能
- 實施適當的資料庫關聯
- 提供完整的 CRUD 操作

### 測試資料
系統已包含測試資料：
- 1 個測試使用者
- 3 個測試專案
- 每個專案 5 個測試待辦事項

## 🤝 貢獻

歡迎提交 Issue 和 Pull Request 來改善這個專案。

## 📄 授權

此專案採用 MIT 授權條款。