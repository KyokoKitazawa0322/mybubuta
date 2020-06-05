set names utf8;
set foreign_key_checks = 0;
drop database if exists ecsite_kitazawa;

create database if not exists ecsite_kitazawa;
use ecsite_kitazawa;

drop table if exists customers;
CREATE TABLE customers (
	customer_id int not null auto_increment primary key, -- �ڋqID
    hash_password   varchar(255) not null,  -- �n�b�V���������p�X���[�h
    last_name       varchar(20) not null,   -- ����
    first_name      varchar(20) not null,   -- ���O
    ruby_last_name  varchar(20) not null,   -- �t���K�i����
    ruby_first_name varchar(20) not null,   -- �t���K�i���O
	zip_code_01     char(3) not null,     -- �X�֔ԍ�(��3��)
    zip_code_02     char(4) not null,     -- �X�֔ԍ�(��4��)
    prefecture      varchar(4) not null,     -- �s���{��
    city            varchar(255) not null,    -- �s�撬��
    block_number    varchar(255) not null,   -- �Ԓn
    building_name   varchar(255),            -- ������
	tel             varchar(11) not null,    -- �ڋq�d�b
	mail            varchar(255) not null unique, -- �ڋq�A�h���X  
	delivery_flag   tinyint(1) not null,          -- �����̔z�B��t���O
	customer_insert_date  datetime not null,      -- �o�^��
    customer_updated_date datetime                -- �X�V��
);


drop table if exists items;
CREATE TABLE items (
	item_code      varchar(10) not null primary key,  -- ���i�R�[�h
	item_name      varchar(255) not null,             -- ���i��
	item_price     int not null,               -- �ŕʒP��
    item_tax       int not null,               -- �����      
	item_category  varchar(255) not null,      -- �J�e�S��
	item_image     varchar(255) not null,      -- ���i�摜
	item_detail    varchar(255) not null,      -- �ڍא���
    item_stock     int not null,               -- �݌�
	delete_flag    tinyint(1) not null,         -- �폜�t���O
	item_insert_date datetime not null,        -- �o�^��
    item_sales     int not null                -- �����
);




drop table if exists order_history;
CREATE TABLE order_history (
	order_id int not null auto_increment primary key,-- �w��ID
	customer_id    int not null,     -- �ڋqID
	total_amount   int not null,     -- ���v���z
    total_quantity int not null,     -- ���v����
    tax            int not null,     -- �����
    postage        int not null,     -- �z����
    payment_term   varchar(30) not null,        -- ���ϕ��@
    delivery_name  varchar(40) not null,        -- �z���戶�� 
    delivery_post  char(8) not null,         -- �z����X�֔ԍ� 
    delivery_addr  varchar(255) not null,       -- �z����
    delivery_tel   varchar(11) not null,        -- �z����d�b�ԍ� 
	purchase_date  datetime not null            -- �w����
);


drop table if exists order_detail;
CREATE TABLE order_detail (
    detail_id     int not null auto_increment primary key,-- ����ID
	order_id      int not null,         -- �w��ID
	item_code     varchar(10) not null, -- ���i�R�[�h
	item_quantity int not null,         -- ����
    item_price    int not null,         -- �ŕʒP��
    item_tax      int not null          -- �����
);


-- �X�V
drop table if exists favorite;
CREATE TABLE favorite (
    favorite_id    int not null auto_increment primary key,  -- ���C�ɓ��菤�iID
	item_code      varchar(10) not null,     -- ���i�R�[�h
	customer_id    int not null              -- �ڋqID
);

drop table if exists delivery;
CREATE TABLE delivery (
    delivery_id     int not null auto_increment primary key, -- �z����ID
    customer_id     int not null,           -- �ڋqID
	last_name       varchar(20) not null,   -- ����
    first_name      varchar(20) not null,   -- ���O
    ruby_last_name  varchar(20) not null,   -- �t���K�i����
    ruby_first_name varchar(20) not null,   -- �t���K�i���O
	zip_code_01     char(3) not null,       -- �X�֔ԍ�(��3��)
    zip_code_02     char(4) not null,       -- �X�֔ԍ�(��4��)
    prefecture      varchar(4) not null,     -- �s���{��
    city            varchar(255) not null,   -- �s�撬��
    block_number    varchar(255) not null,   -- �Ԓn
    building_name   varchar(255),            -- ������
	tel             varchar(11) not null,    -- �ڋq�d�b
	delivery_flag   tinyint(1) not null,     -- �����̔z�B��t���O
	delivery_insert_date     datetime not null,       -- �o�^��
    delivery_updated_date    datetime                 -- �X�V��
);

drop table if exists notice;
CREATE TABLE notice (
	id      int not null auto_increment primary key,
	title   varchar(255) not null,   -- ����
	main_text   text not null,       -- �{��
	insert_date datetime not null    -- �o�^��
);
