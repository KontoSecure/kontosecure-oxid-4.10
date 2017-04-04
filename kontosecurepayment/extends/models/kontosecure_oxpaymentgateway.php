<?php

require_once __DIR__ . '/../../library/kontosecure-client-php/vendor/autoload.php';

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
        $oUser = $oOrder->getOrderUser();
        $oConfig = $this->getConfig();
        $oShop = $oConfig->getActiveShop();
        $sApiKey = $oConfig->getConfigParam('sKontoSecureApiKey');

        $oKontosecureClient = new \KontoSecure\Client($sApiKey);
        $oOrderRequest = new \KontoSecure\Request\CreateOrder();

        // Redirect to this location after successful KontoSecure checkout.
        $sSuccessUrl = $oConfig->getSslShopUrl()
            . '?cl=order&fnc=continueOrder&ksoid={orderId}&orderid=' . $oOrder->oxorder__oxid->value;

        // Redirect to this location when:
        //  - customer canceled the KontoSecure checkout process
        //  - checkout order timed out
        //  - KontoSecure checkout failed
        $sCanceledFailedUrl = $oConfig->getSslShopUrl() . '?cl=payment&fnc=deleteOrder';

        // This location gets called on various points from KontoSecure
        $sWebhookUrl = $oConfig->getSslShopUrl() . '?cl=kontosecure_webhookrc';

        $sDescription = kontosecure_descriptionparser::parse(
            $oConfig->getConfigParam('sKontosecureDescription'),
            $oOrder,
            $oUser,
            $oShop
        );

        $oOrderRequest->setAmount(round($oOrder->oxorder__oxtotalordersum->value, 2))
            ->setClientEmail($oUser->oxuser__oxusername->value)
            ->setDescription($sDescription)
            ->setSuccessUrl($sSuccessUrl)
            ->setFailedUrl($sCanceledFailedUrl)
            ->setCanceledUrl($sCanceledFailedUrl)
            ->setWebhookUrl($sWebhookUrl)
        ;

        $oResponse = $oKontosecureClient->createOrder($oOrderRequest);

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
