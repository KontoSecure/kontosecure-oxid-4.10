<?php

require_once __DIR__ . '/../../library/kontosecure-client-php/vendor/autoload.php';

class kontosecure_order extends kontosecure_order_parent
{
    /**
     * Success return URL
     * @return string
     */
    public function continueOrder()
    {
        $oConfig = $this->getConfig();
        $sKsOrderId = $oConfig->getRequestParameter('ksoid');

        $oSession = new oxSession();

        if (!$this->_isValid($sKsOrderId)) {
            return 'payment';
        }

        // additional check if we really really have a user now
        if (!$oUser = $this->getUser()) {
            return 'user';
        }

        // get basket contents
        $oBasket = $this->getSession()->getBasket();
        if ($oBasket->getProductsCount()) {
            try {
                $oOrder = $oSession->getVariable('kontosecureoxorder');
                $iSuccess = $oOrder->kontosecureFinalizeOrder($oBasket, $oUser);

                // performing special actions after user finishes order (assignment to special user groups)
                $oUser->onOrderExecute($oBasket, $iSuccess);

                // proceeding to next view
                return $this->_getNextStep($iSuccess);
            }
            catch (oxOutOfStockException $oEx) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx, false, true, 'basket');
            }
            catch (oxNoArticleException $oEx) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
            }
            catch (oxArticleInputException $oEx) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
            }
        }
    }

    protected function _isValid($sKsOrderId)
    {
        $sApiKey = $this->getConfig()->getConfigParam('sKontoSecureApiKey');
        $oKontosecureClient = new \KontoSecure\Client($sApiKey);
        $oOrderRequest = new \KontoSecure\Request\GetOrder();
        $oOrderRequest->setOrderId($sKsOrderId);
        $response = $oKontosecureClient->getOrder($oOrderRequest);

        if ($response->isSuccess()
            && $response->getOrderId() == $sKsOrderId
            && $response->getState() == 'closed'
        ) {
            return true;
        }

        return false;
    }
}
