-- ----------------------------------
-- author: KontoSecure
-- ----------------------------------

CREATE TABLE IF NOT EXISTS `kontosecurewebhooklog` (
    `OXID`          VARCHAR(32) COLLATE latin1_general_ci NOT NULL,
    `ORDERID`       VARCHAR(255) COLLATE latin1_general_ci NOT NULL,
    `DESCRIPTION`   VARCHAR(255) COLLATE laatin1_general_ci NOT NULL,
    `TOTALAMOUNT`   DOUBLE NOT NULL,
    `STATE`         VARCHAR(255) COLLATE latin1_general_ci NOT NULL,
    `CANCELPOS`     VARCHAR(255) COLLATE latin1_general_ci,
    `CREATEDAT`     DATETIME NOT NULL,
    PRIMARY KEY (`OXID`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = latin1
    COLLATE = latin1_general_ci;
