<?php

class kontosecure_oxorder extends kontosecure_oxorder_parent
{
    public function finalizeOrder(oxBasket $oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $oSession = new oxSession();
        $sOrderId = $oSession->getVariable('sess_challenge');

        if ($sOrderId) {
            $oDb = oxDb::getDb();
            $sSql = "select oxid from oxorder 
                      where oxpaymenttype = 'kontosecure_dwt' 
                      and oxtransstatus = 'NOT_FINISHED'
                      and oxid = " . $oDb->quote($sOrderId);

            if ($oDb->getOne($sSql)) {
                kontosecure_debug::log('Found unfinished order. Deleting...');
                $this->delete($sOrderId);
                $this->getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID());
                kontosecure_debug::log('Created new order id: ' . $oSession->getVariable('sess_challenge'));
            }
        }

        return parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
    }

    public function kontosecureFinalizeOrder($oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $oSession = new oxSession();
        // payment information
        $oUserPayment = $this->_setPayment($oBasket->getPaymentId());

        // deleting remark info only when order is finished
        $oSession->deleteVariable('ordrem');
        kontosecure_debug::log('Found Ordernr: ' . $this->oxorder__oxordernr->value);

        if (!$this->oxorder__oxordernr->value) {
            kontosecure_debug::log('Ordernr not found. creating...');
            $this->_setNumber();
            kontosecure_debug::log('Ordernr created: ' . $this->oxorder__oxordernr->value);
        } else {
            oxNew('oxCounter')->update($this->_getCounterIdent(), $this->oxorder__oxordernr->value);
        }

        if (!$blRecalculatingOrder) {
            $this->_updateOrderDate();
        }

        // update order status
        $this->_setOrderStatus('OK');

        // save orderid
        $oBasket->setOrderId($this->getId());

        // update wish lists
        $this->_updateWishlist($oBasket->getContents(), $oUser);

        // update users notice list
        $this->_updateNoticeList($oBasket->getContents(), $oUser);

        if (!$blRecalculatingOrder) {
            $this->_markVouchers($oBasket, $oUser);
            $iRet = $this->_sendOrderByEmail($oUser, $oBasket, $oUserPayment);
        } else {
            $iRet = self::ORDER_STATE_OK;
        }

        $iRet2 = $this->finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

        if ($iRet2 == self::ORDER_STATE_ORDEREXISTS) {
            return $iRet;
        }

        return $iRet2;
    }

    public function kontosecureDeleteOrder()
    {
        $oSession = new oxSession();
        $sOrderId = $oSession->getVariable('sess_challenge');

        if ($sOrderId) {
            $oDb = oxDb::getDb();
            $sSql = "select oxid from oxorder 
                where oxpaymenttype = 'kontosecure_dwt' 
                and oxtransstatus = 'NOT_FINISHED'
                and oxid = " . $oDb->quote($sOrderId);

            if ($oDb->getOne($sSql)) {
                $this->delete($sOrderId);
                $this->getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID());
            }
        }
    }

    protected function _executePayment(oxBasket $oBasket, $oUserPayment)
    {
        $sPaymentId = $oBasket->getPaymentId();
        if ($sPaymentId == 'kontosecure_dwt') {
            // Safe current order only ($this) and the current basket ($oBasket) in the session,
            // so that the info are available when the user returns from checkout page.
            $oSession = new oxSession();

            // creates the order number if not set yet
            if (!$this->oxorder__oxordernr->value) {
                $this->_setNumber();
            }

            $oOrder = clone $this;
            $oSession->setVariable('kontosecureoxorder', $oOrder);
        }

        return parent::_executePayment($oBasket, $oUserPayment);
    }
}
