<?php
/*
/**
 * Price enter mode: netto 
 * Price view mode:  brutto
 * Product count: 2
 * VAT info: 19% Default VAT for all Products 
 * Currency rate: 1.0 
 * Discounts: 5
 *  1. shop discount 5abs for product 9005 
 *  2. shop discount 5% for product 9006 
 *  3. basket discount 1 abs for product 9005
 *  4. basket discount 6% for product 9006
 *  5. absolute basket discount 5 abs
 * Vouchers: 1
 // *  1.  vouchers 6.00 abs
 * Trusted Shop:
 *  1. "TS080501_2500_30_EUR"  "netto" => "2.47", "amount" => "1500" ,
 * Wrapping: + 
 * Costs VAT caclulation rule: max 
 * Costs:
 *  1. Payment + 
 *  2. Delivery + 
 *  3. TS -
 * Short description:
 * Trusted shop calculation in Neto-brutto mode,
 */
$aData = array(
    'articles' => array (
        0 => array (
            'oxid'                     => 9005,
            'oxprice'                  => 100,
            'oxvat'                    => 19,
            'amount'                   => 33,
        ),
        1 => array (
            'oxid'                     => 9006,
            'oxprice'                  => 66,
            'oxvat'                    => 19,
            'amount'                   => 16,
        ),
    ),
    'trustedshop' => array (
        'product_id'     => 'TS080501_2500_30_EUR',           // trusted shop product id
        'payments'    => array(                              // paymentids
            'oxidcashondel'  => 'DIRECT_DEBIT',
            'oxidcreditcard' => 'DIRECT_DEBIT',
            'oxiddebitnote'  => 'DIRECT_DEBIT',
            'oxidpayadvance' => 'DIRECT_DEBIT',
            'oxidinvoice'    => 'DIRECT_DEBIT',
            'oxempty'        => 'DIRECT_DEBIT',
        )
    ),
    'discounts' => array (
        0 => array (
            'oxid'         => 'shopdiscount5for9005',
            'oxaddsum'     => 5,
            'oxaddsumtype' => 'abs',
            'oxamount' => 0,
            'oxamountto' => 99999,
            'oxactive' => 1,
            'oxarticles' => array ( 9005 ),
        ),
        1 => array (
            'oxid'         => 'shopdiscount5for9006',
            'oxaddsum'     => 5,
            'oxaddsumtype' => '%',
            'oxamount' => 0,
            'oxamountto' => 99999,
            'oxactive' => 1,
            'oxarticles' => array ( 9006 ),
        ),
        2 => array (
            'oxid'         => 'basketdiscount5for9005',
            'oxaddsum'     => 1,
            'oxaddsumtype' => 'abs',
            'oxamount' => 1,
            'oxamountto' => 99999,
            'oxactive' => 1,
            'oxarticles' => array ( 9005 ),
        ),
        3 => array (
            'oxid'         => 'basketdiscount5for9006',
            'oxaddsum'     => 6,
            'oxaddsumtype' => '%',
            'oxamount' => 1,
            'oxamountto' => 99999,
            'oxactive' => 1,
            'oxarticles' => array ( 9006 ),
        ),
        4 => array (
            'oxid'         => 'absolutebasketdiscount',
            'oxaddsum'     => 5,
            'oxaddsumtype' => 'abs',
            'oxamount' => 1,
            'oxamountto' => 99999,
            'oxactive' => 1,
        ),
    ),
    'costs' => array(
        'wrapping' => array(
            0 => array(
                'oxtype' => 'WRAP',
                'oxname' => 'testWrap9005',
                'oxprice' => 9,
                'oxactive' => 1,
                'oxarticles' => array( 9005 )
            ),
            1 => array(
                'oxtype' => 'WRAP',
                'oxname' => 'testWrap9006',
                'oxprice' => 6,
                'oxactive' => 1,
                'oxarticles' => array( 9006 )
            ),
        ),
        'delivery' => array(
            0 => array(
                'oxtitle' => '6_abs_del',
                'oxactive' => 1,
                'oxaddsum' => 6,
                'oxaddsumtype' => 'abs',
                'oxdeltype' => 'p',
                'oxfinalize' => 1,
                'oxparamend' => 99999
            ),
        ),
        'payment' => array(
            0 => array(
                'oxtitle' => '1 abs payment',
                'oxaddsum' => 1,
                'oxaddsumtype' => 'abs',
                'oxfromamount' => 0,
                'oxtoamount' => 1000000,
                'oxchecked' => 1,
            ),
        ),
        'voucherserie' => array (
            0 => array (
                'oxdiscount' => 6.00,
                'oxdiscounttype' => 'absolute',
                'oxallowsameseries' => 1,
                'oxallowotherseries' => 1,
                'oxallowuseanother' => 1,
                'oxshopincl' => 1,
                'voucher_count' => 1
            ),
        ),
    ),
    'expected' => array (
        'articles' => array (
             9005 => array ( '113,00', '3.729,00' ),
             9006 => array ( '70,13', '1.122,08' ),
        ),
        'totals' => array (
            'totalBrutto' => '4.851,08',
            'totalNetto'  => '4.067,29',
            'vats' => array (
                19 => '772,79'
            ),
            'discounts' => array (
                'absolutebasketdiscount' => '5,00',
            ),
            'wrapping' => array(
                'brutto' => '393,00',
                'netto' => '330,25',
                'vat' => '62,75'
            ),
            'delivery' => array(
                'brutto' => '6,00',
                'netto' => '5,04',
                'vat' => '0,96'
            ),
            'payment' => array(
                'brutto' => '1,00',
                'netto' => '0,84',
                 'vat' => '0,16'
            ),
            'voucher' => array (
                'brutto' => '6,00',
            ),
			'trustedshop' => array(
                'brutto' => '4,90',
                'netto' => '4,12',
                'vat' => '0,78'
            ),
            'grandTotal'  => '5.244,98'
        ),
    ),
    'options' => array (
        'config' => array(
                'blEnterNetPrice' => true,
                'blShowNetPrice' => false,
                'blShowVATForWrapping' => true,
				'sAdditionalServVATCalcMethod' => 'biggest_net', 
                'blShowVATForPayCharge' => true,
                'blShowVATForDelivery' => true,
        ),
        'activeCurrencyRate' => 1.00,
    ),
);
