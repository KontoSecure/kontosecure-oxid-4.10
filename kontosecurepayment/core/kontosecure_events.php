<?php

class kontosecure_events extends kontosecure_base
{
    public static function onActivate()
    {
        oxDb::getDb()->execute(file_get_contents(__DIR__ . '/../sql/webhooklog.sql'));
        oxDb::getDb()->execute(file_get_contents(__DIR__ . '/../sql/oxpayment.sql'));
        static::activatePayment();
    }

    public static function onDeactivate()
    {
        $oPayment = oxNew('oxpayment');
        if ($oPayment->load('kontosecure_dwt')) {
            $oPayment->oxpayments__oxactive = new oxField(0);
            $oPayment->save();
        }

    }

    public static function activatePayment()
    {
        static::setPaymentState(1);
    }

    public static function deactivatePayment()
    {
        static::setPaymentState(0);
    }

    public static function setPaymentState($iState)
    {
        $oPayment = oxNew('oxpayment');
        if ($oPayment->load('kontosecure_dwt')) {
            $oPayment->oxpayments__oxactive = new oxField($iState);
            $oPayment->save();
        }
    }
}
