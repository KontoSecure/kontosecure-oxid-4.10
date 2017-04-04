<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eShop CE
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'kontosecurepayment',
    'title'       => 'KontoSecure',
    'description' => 'KontoSecure Direktüberweisung',
    'thumbnail'   => 'picture.png',
    'version'     => '1.0',
    'author'      => 'KontoSecure',
    'extend'      => array(
        'oxpaymentgateway'   => 'kontosecure/kontosecurepayment/extends/models/kontosecure_oxpaymentgateway',
        'oxorder'            => 'kontosecure/kontosecurepayment/extends/models/kontosecure_oxorder',
        'order'              => 'kontosecure/kontosecurepayment/extends/controllers/kontosecure_order',
        'payment'            => 'kontosecure/kontosecurepayment/extends/controllers/kontosecure_payment',
    ),
    'files'       => array(
        'kontosecure_events'            => 'kontosecure/kontosecurepayment/core/kontosecure_events.php',
        'kontosecure_webhookrc'         => 'kontosecure/kontosecurepayment/application/controllers/kontosecure_webhookrc.php',
        'kontosecure_descriptionparser' => 'kontosecure/kontosecurepayment/application/models/kontosecure_descriptionparser.php',
        'kontosecure_webhooklog'        => 'kontosecure/kontosecurepayment/application/models/kontosecure_webhooklog.php',
        'kontosecure'                   => 'kontosecure/kontosecurepayment/application/controllers/admin/kontosecure.php',
        'kontosecure_main'              => 'kontosecure/kontosecurepayment/application/controllers/admin/kontosecure_main.php',
        'kontosecure_list'              => 'kontosecure/kontosecurepayment/application/controllers/admin/kontosecure_list.php',
    ),
    'templates' => array(
        'kontosecure.tpl' => 'kontosecure/kontosecurepayment/application/views/admin/tpl/kontosecure.tpl',
        'kontosecure_main.tpl' => 'kontosecure/kontosecurepayment/application/views/admin/tpl/kontosecure_main.tpl',
        'kontosecure_list.tpl' => 'kontosecure/kontosecurepayment/application/views/admin/tpl/kontosecure_list.tpl',
    ),
    'blocks'      => array(
        array(
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'select_payment',
            'file'     => 'kontosecure_paymentSelector.tpl',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'mb_select_payment',
            'file'     => 'kontosecure_mb_paymentSelector.tpl',
        ),
    ),
    'events'        => array(
        'onActivate'                        => 'kontosecure_events::onActivate',
        'onDeactivate'                      => 'kontosecure_events::onDeactivate',
    ),
    'settings' => array(
        array(
            'group' => 'main',
            'name'  => 'sKontoSecureApiKey',
            'type'  => 'str',
            'value' => '',
        ),
        array(
            'group' => 'main',
            'name'  => 'sKontosecureDescription',
            'type'  => 'str',
            'value' => 'Bestellnr. {BSTNR}',
        ),
    ),
);
