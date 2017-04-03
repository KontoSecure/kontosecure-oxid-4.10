<?php
require_once __DIR__ . '/../../library/pay-client/vendor/autoload.php';

class kontosecure_oxpaymentgateway extends kontosecure_oxpaymentgateway_parent
{
    /**
     * Executes payment gateway functionality.
     *
     * @extend executePayment
     *
     * @param double $dAmount order price
     * @param oxOrder $oOrder order object
     *
     * @return bool $mReturn
     */
    public function executePayment($dAmount, &$oOrder)
    {
        $oBasket = $this->getSession()->getBasket();
        $sPaymentId = $oBasket->getPaymentId();

        if ('kontosecure_dwt' == $sPaymentId) {
            $mReturn = $this->kontosecureExecutePayment($oOrder);
        } else {
            $mReturn = parent::executePayment($dAmount, $oOrder);
        }

        return $mReturn;
    }

    private function kontosecureExecutePayment(oxOrder $oOrder)
    {
        kontosecure_debug::log('checkout started');
        $oUser = $oOrder->getOrderUser();
        $oConfig = $this->getConfig();
        $oShop = $oConfig->getActiveShop();
        $sApiKey = $oConfig->getConfigParam('sKontoSecureApiKey');
        kontosecure_debug::log('setting api key: ' . $sApiKey);

        $oKontosecureClient = new PayClient\Client($sApiKey);
        $oOrderRequest = new \PayClient\Request\CreateOrder();

        $sSuccessUrl = $oConfig->getSslShopUrl()
            . '?cl=order&fnc=continueOrder&orderid=' . $oOrder->oxorder__oxid->value;

        $sCanceledFailedUrl = $oConfig->getSslShopUrl()
            . '?cl=payment&fnc=deleteOrder';

        $description = kontosecure_descriptionparser::parse(
            $oConfig->getConfigParam('sKontosecureDescription'),
            $oOrder,
            $oUser,
            $oShop
        );

        $oOrderRequest->setAmount(round($oOrder->oxorder__oxtotalordersum->value, 2))
            ->setClientEmail($oUser->oxuser__oxusername->value)
            ->setDescription($description)
            ->setSuccessUrl($sSuccessUrl)
            ->setFailedUrl($sCanceledFailedUrl)
            ->setCanceledUrl($sCanceledFailedUrl)
        ;

        kontosecure_debug::log('sending request');
        $oResponse = $oKontosecureClient->createOrder($oOrderRequest);
        kontosecure_debug::log('got: ' . print_r($oResponse, true));

        if ($oResponse->isSuccess()) {
            $sTransactionId = $oResponse->getOrderId();
            $oOrder->oxorder__oxtransid = new oxField($sTransactionId);
            $oOrder->save();
            $oSession = new oxSession();
            $oSession->setVariable('kontosecureoxorder', $oOrder);

            $sCheckoutUrl = $oResponse->getCheckoutUrl();
            oxRegistry::getUtils()->redirect($sCheckoutUrl);
        }

        return false;
    }
}
