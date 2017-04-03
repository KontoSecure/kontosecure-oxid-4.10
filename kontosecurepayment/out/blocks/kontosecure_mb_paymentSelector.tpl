[{*oxscript include="js/widgets/oxagbcheck.js" priority=10 *}]
[{if $sPaymentID == "kontosecure_dwt"}]
    <div id="paymentOption_[{$sPaymentID}]"
         class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
               [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]
               checked="checked"
               [{/if}]/>
        <div class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <div class="desc">
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
        </div>
    </div>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
