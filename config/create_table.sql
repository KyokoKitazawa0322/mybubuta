set names utf8;
set foreign_key_checks = 0;
drop database if exists ecsite_kitazawa;

create database if not exists ecsite_kitazawa;
use ecsite_kitazawa;

drop table if exists customers;
CREATE TABLE customers (
    customer_id     int not null auto_increment primary key,
	hash_password   varchar(255),     -- パスワード
	last_name       varchar(255),            -- 名字
    first_name      varchar(255),            -- 名前
    ruby_last_name  varchar(255),            -- フリガナ名字
    ruby_first_name varchar(255),            -- フリガナ名前
	address_01      varchar(3),              -- 郵便番号(上3桁)
    address_02      varchar(4),              -- 郵便番号(下4桁)
    address_03      varchar(5),              -- 都道府県
    address_04      varchar(255),            -- 市区町村
    address_05      varchar(255),            -- 番地
    address_06      varchar(255),            -- 建物名
	tel             varchar(11),             -- 顧客電話
	mail            varchar(255) unique,     -- 顧客アドレス  
	del_flag        int(1),                  -- 配達フラグ
	customer_insert_date     datetime,       -- 登録日
    customer_updated_date    datetime        -- 更新日
);


drop table if exists items;
CREATE TABLE items (
	item_code      varchar(6) not null primary key,  -- 商品コード
	item_name      varchar(255),          -- 商品名
	item_price     int(11),               -- 税別単価
    tax            int(11),                -- 消費税      
	item_category  varchar(255),          -- カテゴリ
	item_image     varchar(255),          -- 商品画像
	item_detail    varchar(255),          -- 詳細説明
    item_stock     int(11),               -- 在庫
	item_del_flag  int(1),                -- 削除フラグ
	item_insert_date datetime             -- 登録日,
    item_sales     int(11)                -- 売上個数
);




drop table if exists order_history;
CREATE TABLE order_history (
	order_id int not null auto_increment primary key,-- 購入ID
	customer_id    int,                  -- 顧客コード
	total_payment  int(11),              -- 合計金額
    total_amount   int(11),              -- 合計数量
    tax            int(11),              -- 消費税
    postage        int(11),              -- 配送料
    payment        varchar(30),          -- 決済方法
    delivery_name  varchar(255),         -- 配送先宛名 
    delivery_post  varchar(8),           -- 配送先郵便番号 
    delivery_addr  varchar(255),         -- 配送先
    delivery_tel   varchar(11),          -- 配送先電話番号 
	purchase_date  datetime              -- 購入日
);


drop table if exists order_detail;
CREATE TABLE order_detail (
    detail_id     int not null auto_increment primary key,-- 明細ID
	order_id      int,         -- 購入ID
	item_code     varchar(6),  -- 商品コード
	item_count    int(11),     -- 数量
    item_price    int(11),     -- 税別単価
    item_tax      int(11)      -- 消費税
);


-- 更新
drop table if exists favorite;
CREATE TABLE favorite (
    favorite_id    int not null auto_increment primary key, 
	item_code      varchar(6) not null,  -- 商品コード
	customer_id    int not null
);

drop table if exists delivery;
CREATE TABLE delivery (
    delivery_id     int not null auto_increment  primary key,
    customer_id     int,
	last_name       varchar(255),            -- 名字
    first_name      varchar(255),            -- 名前
    ruby_last_name  varchar(255),            -- フリガナ名字
    ruby_first_name varchar(255),            -- フリガナ名前
	address_01      varchar(3),              -- 郵便番号(上3桁)
    address_02      varchar(4),              -- 郵便番号(下4桁)
    address_03      varchar(5),              -- 都道府県
    address_04      varchar(255),            -- 市区町村
    address_05      varchar(255),            -- 番地
    address_06      varchar(255),            -- 建物名
	tel             varchar(11),             -- 顧客電話
	del_flag        int(1),                  -- 配達フラグ
	delivery_insert_date     datetime,       -- 登録日
    delivery_updated_date    datetime        -- 更新日
);
