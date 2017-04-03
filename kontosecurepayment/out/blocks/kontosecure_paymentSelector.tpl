[{*oxscript include="js/widgets/oxagbcheck.js" priority=10 *}]
[{if $sPaymentID == "kontosecure_dwt"}]
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
                [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}] [{if $paymentmethod->fAddPaymentSum}]([{$paymentmethod->fAddPaymentSum}] [{$currency->sign}])[{/if}]</b></label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <div class="desc">
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
        </dd>
    </dl>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
