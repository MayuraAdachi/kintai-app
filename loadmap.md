# 勤怠管理アプリ開発ロードマップ（Laravel → API化 → React化）

## 目的

- Laravelで業務アプリを一通り作り切る
- API設計の流れを理解する
- 将来的にReactフロントへ置き換えられる構成にする
- ポートフォリオとして公開できるレベルまで仕上げる

---

# 全体構成（3フェーズ）

## Phase1：Laravel（Blade）で完成させる
期間目安：4週間（1日1時間想定）

まずはサーバーサイドのみで完結する勤怠管理アプリを作る。

## Phase2：API化
期間目安：2週間

画面を返すアプリから、JSONを返すAPIへ構成を変更する。

## Phase3：Reactフロント実装
期間目安：2〜3週間

APIを利用するフロントエンドを作成し、最終的に分離構成にする。

---

# Phase1：Laravel + Docker（4週間）

## ゴール

Laravel単体で、最低限の業務アプリとして成立する状態を目指す。

- ログイン機能
- 出勤／退勤の打刻
- 月次一覧表示
- 合計労働時間の算出
- 承認機能（簡易）
- CSV出力

---

## Week1：基礎構築

### Day1-2
- Docker環境構築（nginx / php-fpm / mysql）
- Laravelの起動確認

#### 実施した作業内容

**使用したコマンド**
```bash
# Docker環境確認
docker --version

# Dockerコンテナビルド・起動
docker compose up -d

# コンテナ状態確認
docker compose ps

# Laravelプロジェクト作成（PHPコンテナ内）
docker compose exec php composer create-project laravel/laravel . --prefer-dist
```

### Day3
- Breeze導入（Laravelの公式認証スターターキット）
- usersテーブルにroleカラム追加（admin / employee）

#### 実施した作業内容

**使用したコマンド**
```bash
# Breezeパッケージのインストール
docker compose exec php composer require laravel/breeze --dev

# Breezeのscaffoldingを実行
docker compose exec php php artisan breeze:install

# srcディレクトリでnpm install
cd src ; npm install

# フロントエンドアセットをビルド
cd src ; npm run build

# マイグレーションを実行してデータベース設定を完了
docker compose exec php php artisan migrate
```

- マイグレーションファイルを作成しusersテーブルにroleカラムを追加

### Day4
- attendancesテーブル作成
- マイグレーション確認

#### 実施した作業内容

**使用したコマンド**
```bash
# attendancesテーブル用マイグレーションファイル作成
docker compose exec php php artisan make:migration create_attendances_table
```

- マイグレーションファイル内容：user_id, date, clock_in_time, clock_out_time, break_time_minutes, notes, status
- 全テーブルのカラムにコメント追加（日本語）
- データベース設定をSQLiteからMySQLに変更
- マイグレーション実行でattendancesテーブル作成完了

### Day5
- 出勤機能の実装

#### 実施した作業内容

**使用したコマンド**
```bash
# Attendanceモデル作成
docker compose exec php php artisan make:model Attendance

# AttendanceController作成
docker compose exec php php artisan make:controller AttendanceController
```

**実装した機能**
- Attendanceモデル：fillable設定、Userとのリレーション
- AttendanceController：index()で今日の勤怠情報取得、clockIn()で出勤処理
- ルーティング：/dashboardでAttendanceController@index、POST /attendance/clock-inで出勤
- ダッシュボード画面：今日の勤怠状況表示、出勤ボタン（条件付き表示）
- 重複出勤防止、成功・エラーメッセージ表示

### Day6
- 退勤機能の実装

#### 実施した作業内容

**実装した機能**
- AttendanceController：clockOut()メソッドの実装
- 退勤処理のバリデーション：未出勤・既退勤チェック
- ルーティング：POST /attendance/clock-outの追加
- ダッシュボード画面：退勤ボタンの追加（条件付き表示）
- 退勤ボタンのスタイリング：赤色デザインで出勤ボタンと区別
- 重複退勤防止、エラーハンドリング実装

**動作確認**
- 出勤前：出勤ボタンのみ表示
- 出勤後・退勤前：退勤ボタンのみ表示
- 退勤後：ボタン非表示、勤怠時間のみ表示

### Day7
- 当日の勤怠情報を画面に表示

#### 実施した作業内容

**実装した機能**
- ダッシュボードUIの改善：勤怠情報表示エリアをカード化
- 出勤・退勤時間の見やすい表示：グリッドレイアウトで整理
- 打刻ボタンエリアの中央配置とサイズ拡大
- レスポンシブデザインの適用
- 多言語化対応：__()ヘルパー関数の使用

**UI改善内容**
- 勤怠情報を背景色付きカードで表示
- 出勤・退勤時間を明確に分離表示
- ボタンサイズ拡大（px-8 py-3 text-lg）
- 視覚的階層の整理

まずは「打刻できる状態」を作ることを優先する。

---

## Week2：業務ロジック実装

- 労働時間の計算（Carbon使用）
- 休憩時間の扱いを追加
- 月次一覧画面の作成
- 合計時間の算出処理
- バリデーション整理
- ロジックをServiceクラスへ分離

この週は、コードの整理と業務ロジックの理解を意識する。

---

## Week3：管理機能追加

- statusカラム追加（draft / submitted / approved）
- 提出機能の実装
- 管理者用一覧画面の作成
- 承認／差戻し処理
- Policyの実装
- middlewareでの権限制御

ステータス管理やロール設計を意識して作る。

---

## Week4：仕上げ

- CSV出力機能の実装
- 例外処理の整理
- README作成
- ER図の作成
- 軽いリファクタリング

他人に見せられる状態に整える。

---

# Phase2：API化（2週間）

## ゴール

Laravelを「HTMLを返すアプリ」から「JSONを返すAPI」に変更する。

---

## 実装内容

### APIルート設計

- GET /api/attendances?month=2026-03
- POST /api/clock-in
- POST /api/clock-out
- POST /api/attendances/{id}/submit

### Resourceクラス導入

- AttendanceResource作成
- JSONレスポンス整形

### 認証

- Sanctum導入
- SPA向け認証設定

### 動作確認

- PostmanでAPIテスト

APIとして設計を説明できるレベルを目指す。

---

# Phase3：Reactフロント実装（2〜3週間）

## 使用技術

- Next.js
- React
- Vercel

---

## 実装内容

- ログイン画面作成
- 打刻ボタンUI作成
- 月次一覧表示
- axiosでAPI連携
- 本番環境へデプロイ

LaravelはAPI専用アプリとして扱う。

---

# データベース設計（基本）

## users

- id
- name
- email
- password
- role（admin / employee）

## attendances

- id
- user_id
- work_date
- clock_in
- clock_out
- break_minutes
- status

---

# この構成で身につくこと

- Dockerの基本構成理解
- 業務アプリのDB設計
- トランザクション設計
- Service層へのロジック分離
- API設計の基礎
- フロント／バックエンド分離構成の理解

---

# 最終イメージ

- 業務アプリを一人で設計〜実装できる
- API化の流れを理解している
- フロント分離構成を説明できる
- ポートフォリオとして提示できる

---

# 次にやること

1. Docker構成を作る
2. ER設計をもう少し具体化する
3. マイグレーション設計から始める

今日の1時間でやる内容を決めて着手する。
