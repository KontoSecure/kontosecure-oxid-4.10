<?php

class kontosecure_webhookrc extends oxUBase
{
    public function render()
    {
        parent::render();
        kontosecure_debug::log('received hook');

        $aWebhook = $this->_getKontosecureWebhookArray();
        $this->_kontosecureWebhookLog($aWebhook);

        $sState = $aWebhook['state'];

        switch ($sState) {
            case 'closed':
                $this->_setKontosecureOrderPayed($aWebhook);
                break;
            case 'failed':
            case 'canceled':
                // do nothing here. Already handled by failed / canceled return URL
                break;
        }

        oxRegistry::getUtils()->showMessageAndExit('OK');
    }

    protected function _getKontosecureWebhookArray()
    {
        $aWebhook = array();
        $content = file_get_contents('php://input');

        if (!empty($content)) {
            $aWebhook = @json_decode($content, true);
            if (!is_array($aWebhook)) {
                $aWebhook = array();
            }
        }

        return $aWebhook;
    }

    protected function _setKontosecureOrderPayed(array $aWebhook)
    {
        $sOrderId = $this->_getKontosecureOxId($aWebhook['order_id']);
        if ($sOrderId) {
            $oDB = oxDb::getDb();
            $sDate = $aWebhook['transaction']['created_at'];

            $oDB->execute(
                'UPDATE oxorder SET oxpaid = ? where oxid = ?',
                array($sDate, $sOrderId)
            );
        }
    }

    protected function _getKontosecureOxId($sTransactionId)
    {
        return oxDb::getDb()->getOne(
            'SELECT OXID FROM oxorder where oxtransid = ?',
            array($sTransactionId)
        );
    }

    /**
     * @param array $aWebhook
     */
    private function _kontosecureWebhookLog($aWebhook)
    {
        $oKontosecureWebhookLog = oxNew('kontosecure_webhooklog');
        $oKontosecureWebhookLog->kontosecurewebhooklog__orderid = new oxField($aWebhook['order_id']);
        $oKontosecureWebhookLog->kontosecurewebhooklog__state = new oxField($aWebhook['state']);
        $oKontosecureWebhookLog->kontosecurewebhooklog__cancelpos = new oxField($aWebhook['cancel_position']);
        $oKontosecureWebhookLog->kontosecurewebhooklog__createdat = new oxField($aWebhook['created_at']);
        $oKontosecureWebhookLog->save();
    }

}
