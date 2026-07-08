<?php
/**
 * Copyright 2018 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$sLangName = "Slovenčina";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Chyba pri overovaní objednávky',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Zľava',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Podanie',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Zľavový kupón',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Darčekové balenie',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Blahoželanie',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Príplatok za spôsob platby',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Poplatok za ochranu kupujúcich od Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Heslo',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Ochrana kupujúcich od Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Už ste našim zákazníkom?',
    'TCKLARNA_LAW_NOTICE'                               => 'Platia podmienky <a href="%s" class="klarna-notification" target="_blank">používania</a> týkajúce sa prenosu údajov',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Máte poukaz?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'K pokladni',
    'TCKLARNA_BUY_NOW'                                  => 'Kúpiť teraz',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Použiť ako dodaciu adresu',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Vyberte dodaciu adresu',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Vytvoriť zákaznícky účet',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Prihlásiť sa k odberu newslettra',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Vytvorenie zákazníckeho účtu A prihlásenie sa k odberu noviniek',
    'TCKLARNA_NO_CHECKBOX'                              => 'Nezobrazovať zaškrtávacie políčko',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Doručovacia adresa sa môže líšiť od fakturačnej adresy',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Dátum narodenia ako povinné pole',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Vyberte prosím krajinu dodania:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Vaša krajina tu nie je?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Ďalšie krajiny dodávok',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Moja krajina nie je v zozname',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Iné krajiny',
    'TCKLARNA_RESET_COUNTRY'                            => 'Vaša krajina: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'zmeniť',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Kliknite prosím na toto tlačidlo, aby ste začali prihlásenie cez Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Pozor!</strong> Údaje tejto objednávky sa líšia od údajov uložených v systéme Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Objednávka bola zrušená. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Túto objednávku si môžete zobraziť v portáli Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Došlo k chybe. Skúste to prosím znova.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Chyba v konfigurácii – skontrolujte URL adresy odkazujúce na VOP a právo na odstúpenie od zmluvy',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Aby bolo možné použiť tento spôsob platby cez Klarna, musia sa údaje o osobe a krajine v fakturačnej a dodacej adrese zhodovať.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Neplatný autorizačný token. Skúste to prosím znova.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Údaje o objednávke sa zmenili.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Aby ste mohli použiť platobnú metódu Klarna, musí sa zvolená mena zhodovať s oficiálnou menou krajiny, v ktorej je vystavená faktúra alebo do ktorej sa tovar doručuje.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Chyba konfigurácie: V tejto krajine nie sú k dispozícii žiadne platobné metódy Klarna.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Platba prostredníctvom tejto platobnej metódy Klarna nie je v súčasnosti k dispozícii pre objednávky od firiem.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Platba prostredníctvom tejto platobnej metódy Klarna je k dispozícii len pre objednávky od súkromných osôb.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Platba prostredníctvom tejto platobnej metódy Klarna je v súčasnosti k dispozícii len pre objednávky od firiem.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Prosím, súhlaste s Všeobecnými obchodnými podmienkami a podmienkami odstúpenia od zmluvy týkajúcimi sa digitálneho obsahu.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Nedostatočné skladové zásoby produktu „%s“.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'V súčasnosti nie je pre túto krajinu definovaný žiadny spôsob doručenia: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Najprv nakúpiť, potom zaplatiť',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Pohodlné splácanie na splátky',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Platiť jednoducho a priamo',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Hodnota objednávky je príliš vysoká.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Použitý spôsob platby: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Kreditná karta Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Inkaso Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna – okamžitý prevod',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna – 3 bezúročné splátky',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Financovanie prostredníctvom Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Faktúra Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Faktúra Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Platba kartou cez Klarna s 30-dňovou splatnosťou',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymizovaný názov produktu:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Niečo sa nepodarilo. Obnov stránku a skús to znova.',
];
