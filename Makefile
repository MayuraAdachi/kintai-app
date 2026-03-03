# Docker操作を簡単にするためのMakefile

.PHONY: help build up down logs shell composer artisan

help: ## ヘルプを表示
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Dockerイメージをビルド
	docker-compose build

up: ## コンテナを起動
	docker-compose up -d

down: ## コンテナを停止・削除
	docker-compose down

logs: ## ログを表示
	docker-compose logs -f

shell: ## PHPコンテナにアクセス
	docker-compose exec php bash

composer: ## Composerコマンドを実行 (例: make composer ARGS="install")
	docker-compose exec php composer $(ARGS)

artisan: ## Artisanコマンドを実行 (例: make artisan ARGS="migrate")
	docker-compose exec php php artisan $(ARGS)

laravel-install: ## Laravelプロジェクトを作成
	docker-compose exec php composer create-project laravel/laravel . --prefer-dist

init: ## 初期セットアップ（Laravel作成 + 依存関係インストール）
	make up
	sleep 10
	make laravel-install
	make artisan ARGS="key:generate"

fresh: ## 完全リセット（コンテナ削除 + ボリューム削除）
	docker-compose down -v
	docker-compose build --no-cache
