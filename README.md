# このリポジトリについて
Laravel Sailを使用した環境構築及び、Laravel(非VueSPA)の環境でselect2とyubinbango.jsの住所自動入力を組み合せたプログラムのサンプル  
入力した郵便番号を元にselect2を使用した都道府県と市区町村のプルダウンを動的に切り替える  
市区町村は都道府県の選択値を元にあらかじめpublic以下に廃止した市区町村の一覧jsonを参照して動的にプルダウンの選択肢を切り替える  
jquery,select2,bootstrap4などについてlaravel-mixは使用せずCDNの参照とする

## クローン後の環境構築手順（未検証）
```
composer install
cp .env.example .env
php artisan key:generate
wsl
vendor/bin/sail up -d
```
