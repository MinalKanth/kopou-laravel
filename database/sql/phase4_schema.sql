-- =====================================================================
-- KOPOU — Phase 4 schema (catalog only)
-- Matches database/migrations/2025_01_01_0000{1..5}_*.php exactly.
-- Engine: InnoDB, utf8mb4 for full emoji/Assamese-script support.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- categories
-- ---------------------------------------------------------------------
CREATE TABLE categories (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id       BIGINT UNSIGNED NULL,
    name            VARCHAR(150) NOT NULL,
    slug            VARCHAR(160) NOT NULL,
    image           VARCHAR(255) NULL,
    description     TEXT NULL,
    sort_order      INT UNSIGNED NOT NULL DEFAULT 0,
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    UNIQUE KEY uq_categories_slug (slug),
    KEY idx_categories_active (is_active),
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- products
-- ---------------------------------------------------------------------
CREATE TABLE products (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id         BIGINT UNSIGNED NOT NULL,
    name                VARCHAR(255) NOT NULL,
    slug                VARCHAR(280) NOT NULL,
    sku                 VARCHAR(100) NOT NULL,
    brand               VARCHAR(150) NULL,
    origin              VARCHAR(150) NULL,

    price               DECIMAL(12,2) NOT NULL,
    sale_price          DECIMAL(12,2) NULL,

    weight              VARCHAR(50) NULL,
    material            VARCHAR(150) NULL,

    short_description   TEXT NULL,
    description         LONGTEXT NULL,
    care_instructions   TEXT NULL,
    specifications      JSON NULL,
    badges              JSON NULL,

    rating              DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    review_count        INT UNSIGNED NOT NULL DEFAULT 0,

    is_featured         TINYINT(1) NOT NULL DEFAULT 0,
    is_bestseller       TINYINT(1) NOT NULL DEFAULT 0,
    status              ENUM('draft','active','inactive') NOT NULL DEFAULT 'active',

    seo_title           VARCHAR(255) NULL,
    seo_description     VARCHAR(320) NULL,

    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    deleted_at          TIMESTAMP NULL,

    UNIQUE KEY uq_products_slug (slug),
    UNIQUE KEY uq_products_sku (sku),
    KEY idx_products_status_featured (status, is_featured),
    KEY idx_products_status_bestseller (status, is_bestseller),
    KEY idx_products_category (category_id),
    FULLTEXT KEY ft_products_name_desc (name, short_description),
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- product_images
-- ---------------------------------------------------------------------
CREATE TABLE product_images (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id  BIGINT UNSIGNED NOT NULL,
    path        VARCHAR(255) NOT NULL,
    alt_text    VARCHAR(255) NULL,
    sort_order  INT UNSIGNED NOT NULL DEFAULT 0,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,
    KEY idx_product_images_product_sort (product_id, sort_order),
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- product_variants
-- ---------------------------------------------------------------------
CREATE TABLE product_variants (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id  BIGINT UNSIGNED NOT NULL,
    label       VARCHAR(100) NOT NULL,
    sku         VARCHAR(100) NOT NULL,
    price       DECIMAL(12,2) NOT NULL,
    sale_price  DECIMAL(12,2) NULL,
    sort_order  INT UNSIGNED NOT NULL DEFAULT 0,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,
    UNIQUE KEY uq_product_variants_sku (sku),
    KEY idx_product_variants_product (product_id),
    CONSTRAINT fk_product_variants_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- inventories
-- ---------------------------------------------------------------------
CREATE TABLE inventories (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id          BIGINT UNSIGNED NOT NULL,
    stock_quantity      INT UNSIGNED NOT NULL DEFAULT 0,
    reserved_quantity   INT UNSIGNED NOT NULL DEFAULT 0,
    low_stock_threshold INT UNSIGNED NOT NULL DEFAULT 5,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    UNIQUE KEY uq_inventories_product (product_id),
    CONSTRAINT fk_inventories_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
