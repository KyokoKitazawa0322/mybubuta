set names utf8;
set foreign_key_checks = 0;
drop database if exists ecsite_kitazawa;

create database if not exists ecsite_kitazawa;
use ecsite_kitazawa;

drop table if exists customers;
CREATE TABLE customers (
    customer_id     int not null auto_increment primary key,
	hash_password   varchar(255),     -- �p�X���[�h
	last_name       varchar(255),            -- ����
    first_name      varchar(255),            -- ���O
    ruby_last_name  varchar(255),            -- �t���K�i����
    ruby_first_name varchar(255),            -- �t���K�i���O
	address_01      varchar(3),              -- �X�֔ԍ�(��3��)
    address_02      varchar(4),              -- �X�֔ԍ�(��4��)
    address_03      varchar(5),              -- �s���{��
    address_04      varchar(255),            -- �s�撬��
    address_05      varchar(255),            -- �Ԓn
    address_06      varchar(255),            -- ������
	tel             varchar(11),             -- �ڋq�d�b
	mail            varchar(255) unique,     -- �ڋq�A�h���X  
	del_flag        int(1),                  -- �z�B�t���O
	customer_insert_date     datetime,       -- �o�^��
    customer_updated_date    datetime        -- �X�V��
);


drop table if exists items;
CREATE TABLE items (
	item_code      varchar(6) not null primary key,  -- ���i�R�[�h
	item_name      varchar(255),          -- ���i��
	item_price     int(11),               -- �ŕʒP��
    tax            int(11),                -- �����      
	item_category  varchar(255),          -- �J�e�S��
	item_image     varchar(255),          -- ���i�摜
	item_detail    varchar(255),          -- �ڍא���
    item_stock     int(11),               -- �݌�
	item_del_flag  int(1),                -- �폜�t���O
	item_insert_date datetime             -- �o�^��,
    item_sales     int(11)                -- �����
);




drop table if exists order_history;
CREATE TABLE order_history (
	order_id int not null auto_increment primary key,-- �w��ID
	customer_id    int,                  -- �ڋq�R�[�h
	total_payment  int(11),              -- ���v���z
    total_amount   int(11),              -- ���v����
    tax            int(11),              -- �����
    postage        int(11),              -- �z����
    payment        varchar(30),          -- ���ϕ��@
    delivery_name  varchar(255),         -- �z���戶�� 
    delivery_post  varchar(8),           -- �z����X�֔ԍ� 
    delivery_addr  varchar(255),         -- �z����
    delivery_tel   varchar(11),          -- �z����d�b�ԍ� 
	purchase_date  datetime              -- �w����
);


drop table if exists order_detail;
CREATE TABLE order_detail (
    detail_id     int not null auto_increment primary key,-- ����ID
	order_id      int,         -- �w��ID
	item_code     varchar(6),  -- ���i�R�[�h
	item_count    int(11),     -- ����
    item_price    int(11),     -- �ŕʒP��
    item_tax      int(11)      -- �����
);


-- �X�V
drop table if exists favorite;
CREATE TABLE favorite (
    favorite_id    int not null auto_increment primary key, 
	item_code      varchar(6) not null,  -- ���i�R�[�h
	customer_id    int not null
);

drop table if exists delivery;
CREATE TABLE delivery (
    delivery_id     int not null auto_increment  primary key,
    customer_id     int,
	last_name       varchar(255),            -- ����
    first_name      varchar(255),            -- ���O
    ruby_last_name  varchar(255),            -- �t���K�i����
    ruby_first_name varchar(255),            -- �t���K�i���O
	address_01      varchar(3),              -- �X�֔ԍ�(��3��)
    address_02      varchar(4),              -- �X�֔ԍ�(��4��)
    address_03      varchar(5),              -- �s���{��
    address_04      varchar(255),            -- �s�撬��
    address_05      varchar(255),            -- �Ԓn
    address_06      varchar(255),            -- ������
	tel             varchar(11),             -- �ڋq�d�b
	del_flag        int(1),                  -- �z�B�t���O
	delivery_insert_date     datetime,       -- �o�^��
    delivery_updated_date    datetime        -- �X�V��
);
