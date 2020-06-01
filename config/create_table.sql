set names utf8;
set foreign_key_checks = 0;
drop database if exists ecsite_kitazawa;

create database if not exists ecsite_kitazawa;
use ecsite_kitazawa;

drop table if exists customers;
CREATE TABLE customers (
    customer_id     int not null auto_increment primary key,
	hash_password   varchar(255),     -- pX[h
	last_name       varchar(255),            -- ¼
    first_name      varchar(255),            -- ¼O
    ruby_last_name  varchar(255),            -- tKi¼
    ruby_first_name varchar(255),            -- tKi¼O
	address_01      varchar(3),              -- XÖÔ(ã3)
    address_02      varchar(4),              -- XÖÔ(º4)
    address_03      varchar(5),              -- s¹{§
    address_04      varchar(255),            -- sæ¬º
    address_05      varchar(255),            -- Ôn
    address_06      varchar(255),            -- ¨¼
	tel             varchar(11),             -- Úqdb
	mail            varchar(255) unique,     -- ÚqAhX  
	del_flag        int(1),                  -- zBtO
	customer_insert_date     datetime,       -- o^ú
    customer_updated_date    datetime        -- XVú
);


drop table if exists items;
CREATE TABLE items (
	item_code      varchar(6) not null primary key,  -- ¤iR[h
	item_name      varchar(255),          -- ¤i¼
	item_price     int(11),               -- ÅÊP¿
    tax            int(11),                -- ÁïÅ      
	item_category  varchar(255),          -- JeS
	item_image     varchar(255),          -- ¤iæ
	item_detail    varchar(255),          -- Ú×à¾
    item_stock     int(11),               -- ÝÉ
	item_del_flag  int(1),                -- ítO
	item_insert_date datetime             -- o^ú,
    item_sales     int(11)                -- ãÂ
);




drop table if exists order_history;
CREATE TABLE order_history (
	order_id int not null auto_increment primary key,-- wüID
	customer_id    int,                  -- ÚqR[h
	total_payment  int(11),              -- vàz
    total_amount   int(11),              -- vÊ
    tax            int(11),              -- ÁïÅ
    postage        int(11),              -- z¿
    payment        varchar(30),          -- Ïû@
    delivery_name  varchar(255),         -- zæ¶¼ 
    delivery_post  varchar(8),           -- zæXÖÔ 
    delivery_addr  varchar(255),         -- zæ
    delivery_tel   varchar(11),          -- zædbÔ 
	purchase_date  datetime              -- wüú
);


drop table if exists order_detail;
CREATE TABLE order_detail (
    detail_id     int not null auto_increment primary key,-- ¾×ID
	order_id      int,         -- wüID
	item_code     varchar(6),  -- ¤iR[h
	item_count    int(11),     -- Ê
    item_price    int(11),     -- ÅÊP¿
    item_tax      int(11)      -- ÁïÅ
);


-- XV
drop table if exists favorite;
CREATE TABLE favorite (
    favorite_id    int not null auto_increment primary key, 
	item_code      varchar(6) not null,  -- ¤iR[h
	customer_id    int not null
);

drop table if exists delivery;
CREATE TABLE delivery (
    delivery_id     int not null auto_increment  primary key,
    customer_id     int,
	last_name       varchar(255),            -- ¼
    first_name      varchar(255),            -- ¼O
    ruby_last_name  varchar(255),            -- tKi¼
    ruby_first_name varchar(255),            -- tKi¼O
	address_01      varchar(3),              -- XÖÔ(ã3)
    address_02      varchar(4),              -- XÖÔ(º4)
    address_03      varchar(5),              -- s¹{§
    address_04      varchar(255),            -- sæ¬º
    address_05      varchar(255),            -- Ôn
    address_06      varchar(255),            -- ¨¼
	tel             varchar(11),             -- Úqdb
	del_flag        int(1),                  -- zBtO
	delivery_insert_date     datetime,       -- o^ú
    delivery_updated_date    datetime        -- XVú
);
