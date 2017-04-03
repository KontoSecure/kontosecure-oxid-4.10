<?php

class kontosecure_descriptionparser
{
    const KEY_ORDER_NUMBER = '{ORDNR}';
    const KEY_CUSTOMER_NUMBER = '{CNR}';
    const KEY_DATE = '{DATE}';
    const KEY_SHOPNAME = '{SHOP}';

    /**
     * Replaces placeholder with actual values.
     *
     * @param string $description
     * @param oxOrder $oOrder
     * @param oxUser $oUser
     * @param oxShop $oShop
     * @return string
     */
    public static function parse($description, oxOrder $oOrder, oxUser $oUser, oxShop $oShop)
    {
        $placeholder = array(
            static::KEY_ORDER_NUMBER,
            static::KEY_CUSTOMER_NUMBER,
            static::KEY_DATE,
            static::KEY_SHOPNAME,
        );

        $replacement = array(
            $oOrder->oxorder__oxordernr->value,
            $oUser->oxuser__oxcustnr->value,
            date('d.m.Y'),
            $oShop->oxshops__oxname->value,
        );

        return str_replace($placeholder, $replacement, $description);
    }
}
