<?php
/* RRP = 299
 * Price enter mode: brutto
 * Price view mode: brutto
 * Product count: 4
 * VAT info: 15
 * Discount number: 4
 *  1. shop; %
 */
$aData = array (
        'articles' => array (
                0 => array (
                        'oxid'            => '1000',
                        'oxprice'         => 150,
                        'oxtprice'        => 299
                ),
                1 => array (
                        'oxid'            => '1001',
                        'oxprice'         => 150,
                        'oxtprice'        => 299
                ),
                2 => array (
                        'oxid'            => '1002',
                        'oxprice'         => 150,
                        'oxtprice'        => 299
                ),
                3 => array (
                        'oxid'            => '1003',
                        'oxprice'         => 150,
                        'oxtprice'        => 299
                ),
        ),
        'discounts' => array (
                0 => array (
                        'oxid'             => 'percentFor1000',
                        'oxaddsum'         => 20,
                        'oxaddsumtype'     => '%',
                        'oxprice'          => 0,
                        'oxpriceto'        => 99999,
                        'oxamount'         => 0,
                        'oxamountto'       => 99999,
                        'oxactive'         => 1,
                        'oxarticles'       => array ( 1000 ),
                ),
                1 => array (
                        'oxid'         => 'percentFor1001',
                        'oxaddsum'     => -10,
                        'oxaddsumtype' => '%',
                        'oxprice'    => 0,
                        'oxpriceto' => 99999,
                        'oxamount' => 0,
                        'oxamountto' => 99999,
                        'oxactive' => 1,
                        'oxarticles' => array ( 1001 ),
                ),
                2 => array (
                        'oxid'             => 'percentFor1002',
                        'oxaddsum'         => -5.2,
                        'oxaddsumtype'     => '%',
                        'oxprice'          => 0,
                        'oxpriceto'        => 99999,
                        'oxamount'         => 0,
                        'oxamountto'       => 99999,
                        'oxactive'         => 1,
                        'oxarticles'       => array ( 1002 ),
                ),
                3 => array (
                        'oxid'         => 'percentFor1003',
                        'oxaddsum'     => 5.5,
                        'oxaddsumtype' => '%',
                        'oxprice'    => 0,
                        'oxpriceto' => 99999,
                        'oxamount' => 0,
                        'oxamountto' => 99999,
                        'oxactive' => 1,
                        'oxarticles' => array ( 1003 ),
                ),
        ),
        'expected' => array (
                1000 => array (
                        'base_price'        => '150,00',
                        'price'             => '120,00',
                        'rrp_price'         => '299,00',
                        'show_rrp'          => true
                ),
                1001 => array (
                        'base_price'        => '150,00',
                        'price'             => '165,00',
                        'rrp_price'         => '299,00',
                        'show_rrp'          => true
                ),
                1002 => array (
                        'base_price'        => '150,00',
                        'price'             => '157,80',
                        'rrp_price'         => '299,00',
                        'show_rrp'          => true
                ),
                1003 => array (
                        'base_price'        => '150,00',
                        'price'             => '141,75',
                        'rrp_price'         => '299,00',
                        'show_rrp'          => true
                ),
        ),
        'options' => array (
                'config' => array(
                        'blEnterNetPrice' => false,
                        'blShowNetPrice' => false,
                        'dDefaultVAT' => 15,
                ),
                'activeCurrencyRate' => 1,
        ),
);
