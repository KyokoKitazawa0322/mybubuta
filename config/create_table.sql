set names utf8;
set foreign_key_checks = 0;
drop database if exists ecsite_kitazawa;

create database if not exists ecsite_kitazawa;
use ecsite_kitazawa;

drop table if exists customers;
CREATE TABLE customers (
	customer_id int not null auto_increment primary key, -- 顧客ID
    hash_password   varchar(255) not null,  -- ハッシュ化したパスワード
    last_name       varchar(20) not null,   -- 名字
    first_name      varchar(20) not null,   -- 名前
    ruby_last_name  varchar(20) not null,   -- フリガナ名字
    ruby_first_name varchar(20) not null,   -- フリガナ名前
	zip_code_01     char(3) not null,     -- 郵便番号(上3桁)
    zip_code_02     char(4) not null,     -- 郵便番号(下4桁)
    prefecture      varchar(4) not null,     -- 都道府県
    city            varchar(255) not null,    -- 市区町村
    block_number    varchar(255) not null,   -- 番地
    building_name   varchar(255),            -- 建物名
	tel             varchar(11) not null,    -- 顧客電話
	mail            varchar(255) not null unique, -- 顧客アドレス  
	delivery_flag   tinyint(1) not null,          -- いつもの配達先フラグ
	customer_insert_date  datetime not null,      -- 登録日
    customer_updated_date datetime                -- 更新日
);


drop table if exists items;
CREATE TABLE items (
	item_code      varchar(10) not null primary key,  -- 商品コード
	item_name      varchar(255) not null,             -- 商品名
	item_price     int not null,               -- 税別単価
    item_tax       int not null,               -- 消費税      
	item_category  varchar(255) not null,      -- カテゴリ
	item_image     varchar(255) not null,      -- 商品画像
	item_detail    varchar(255) not null,      -- 詳細説明
    item_stock     int not null,               -- 在庫
	delete_flag    tinyint(1) not null,         -- 削除フラグ
	item_insert_date datetime not null,        -- 登録数
    item_sales     int not null                -- 売上個数
);




drop table if exists order_history;
CREATE TABLE order_history (
	order_id int not null auto_increment primary key,-- 購入ID
	customer_id    int not null,     -- 顧客ID
	total_amount   int not null,     -- 合計金額
    total_quantity int not null,     -- 合計数量
    tax            int not null,     -- 消費税
    postage        int not null,     -- 配送料
    payment_term   varchar(30) not null,        -- 決済方法
    delivery_name  varchar(40) not null,        -- 配送先宛名 
    delivery_post  char(8) not null,         -- 配送先郵便番号 
    delivery_addr  varchar(255) not null,       -- 配送先
    delivery_tel   varchar(11) not null,        -- 配送先電話番号 
	purchase_date  datetime not null            -- 購入日
);


drop table if exists order_detail;
CREATE TABLE order_detail (
    detail_id     int not null auto_increment primary key,-- 明細ID
	order_id      int not null,         -- 購入ID
	item_code     varchar(10) not null, -- 商品コード
	item_quantity int not null,         -- 数量
    item_price    int not null,         -- 税別単価
    item_tax      int not null          -- 消費税
);


-- 更新
drop table if exists favorite;
CREATE TABLE favorite (
    favorite_id    int not null auto_increment primary key,  -- お気に入り商品ID
	item_code      varchar(10) not null,     -- 商品コード
	customer_id    int not null              -- 顧客ID
);

drop table if exists delivery;
CREATE TABLE delivery (
    delivery_id     int not null auto_increment primary key, -- 配送先ID
    customer_id     int not null,           -- 顧客ID
	last_name       varchar(20) not null,   -- 名字
    first_name      varchar(20) not null,   -- 名前
    ruby_last_name  varchar(20) not null,   -- フリガナ名字
    ruby_first_name varchar(20) not null,   -- フリガナ名前
	zip_code_01     char(3) not null,       -- 郵便番号(上3桁)
    zip_code_02     char(4) not null,       -- 郵便番号(下4桁)
    prefecture      varchar(4) not null,     -- 都道府県
    city            varchar(255) not null,   -- 市区町村
    block_number    varchar(255) not null,   -- 番地
    building_name   varchar(255),            -- 建物名
	tel             varchar(11) not null,    -- 顧客電話
	delivery_flag   tinyint(1) not null,     -- いつもの配達先フラグ
	delivery_insert_date     datetime not null,       -- 登録日
    delivery_updated_date    datetime                 -- 更新日
);

drop table if exists notice;
CREATE TABLE notice (
	id      int not null auto_increment primary key,
	title   varchar(255) not null,   -- 件名
	main_text   text not null,       -- 本文
	insert_date datetime not null    -- 登録日
);
