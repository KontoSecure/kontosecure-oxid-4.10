-- ----------------------------------
-- author: KontoSecure
-- ----------------------------------

INSERT IGNORE INTO `oxpayments`
SET
    `OXID`          = 'kontosecure_dwt',
    `OXACTIVE`      = 1,
    `OXDESC`        = 'KontoSecure',
    `OXDESC_1`      = 'KontoSecure Direktüberweisung',
    `OXADDSUM`      = 0,
    `OXADDSUMTYPE`  = 'abs',
    `OXADDSUMRULES` = 15,
    `OXFROMBONI`    = 0,
    `OXFROMAMOUNT`  = 0,
    `OXTOAMOUNT`    = 999999,
    `OXCHECKED`     = 1,
    `OXSORT`        = 1,
    `OXLONGDESC`    = '<div id="payment_form_kontosecure">
           <ul>
             <li>Direkt&uuml;berweisung mit PIN / TAN</li>
             <li>Keine Registrierung notwendig</li>
             <li>Sicher, einfach und schnell</li>
             <li>Ihre Onlinebanking Zugangsdaten werden nicht gespeichert</li>
           </ul>
        </div>
        <div class="clear"></div>',
    `OXLONGDESC_1`  = '<div id="payment_form_kontosecure">
           <ul>
             <li>Direct Wire Transfer with PIN / TAN</li>
             <li>No registration required</li>
             <li>Secure, easy and fast</li>
             <li>Your Online Banking credentials will not be saved</li>
           </ul>
        </div>
        <div class="clear"></div>'
