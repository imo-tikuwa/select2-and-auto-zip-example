# このリポジトリについて
- Laravel Sailを使用した環境構築及び、Laravel(非VueSPA)の環境でselect2とyubinbango.jsの住所自動入力を組み合せたプログラムのサンプル
  - 入力した郵便番号を元にselect2を使用した都道府県と市区町村のプルダウンを動的に切り替える
  - 市区町村は都道府県の選択値を元にあらかじめpublic以下に廃止した市区町村の一覧jsonを参照して動的にプルダウンの選択肢を切り替える
  - 市区町村の一覧は[RESAS](https://opendata.resas-portal.go.jp/)の市区町村APIから取得したものを使用
    - .envの`RESAS_API_KEY`にAPIキーをセットした状態で`php artisan create:city_json`実行することでpublic以下に最新のjsonを配置
    - もしかしたらyubinbango.jsが返す市区町村と一致しないケースがあるかもしれない
- jquery,select2,bootstrap4などについてlaravel-mixは使用せずCDNの参照とする
- `sail up -d`後、`http://localhost`にアクセスすることで動作確認可能
  - フォーム内の送信ボタンはPOST送信後の値保持が出来てることを確認するためのもの

---
![image](https://user-images.githubusercontent.com/48991931/137619069-1e0d6b25-807c-4ef7-8cbe-38240d0c88c6.png)

## クローン後の環境構築手順（未検証）
```
composer install
cp .env.example .env
php artisan key:generate
wsl
vendor/bin/sail up -d
```
.envの改行コードがCRLFだと`/.env: line 〇〇: $'\r': command not found`というエラーが出るためLFに変換しておいた方が良いかもしれない。
