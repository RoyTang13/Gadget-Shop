----------------------------== PRODUCT DATABASE ==----------------------------

CREATE TABLE 'product' (
    'productID'    CHAR(5) NOT NULL,
    'productName'  VARCHAR(150) NOT NULL,
    'productPrice' DECIMAL(6,2) NOT NULL,
    'productDesc'  VARCHAR(1000) NOT NULL,
    'productQty'   INT(3) NOT NULL,
    'productCat1'  ENUM('Wired', 'Wireless') NOT NULL,
    'productCat2'  ENUM('In-Ear', 'Over-Ear') NOT NULL,
    'productCat3'  ENUM('Noise-Canceled', 'Balanced', 'Clear Vocals') NOT NULL,
    'productPhoto' VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

INSERT INTO 'product' ('productID', 'productName', 'productPrice', 'productDesc', 'productQty', 'productCat1', 'productCat2', 'productCat3', 'productPhoto')

ALTER TABLE 'product'
    ADD PRIMARY KEY (productID);