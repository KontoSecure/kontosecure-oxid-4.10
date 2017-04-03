<?php

class kontosecure_payment extends kontosecure_payment_parent
{
    public function deleteOrder()
    {
        $sOrderId = $this->getSession()->getVariable('sess_challenge');
        $oOrder = oxNew('oxorder');
        $oOrder->load($sOrderId);
        $oOrder->kontosecureDeleteOrder();
    }
}
