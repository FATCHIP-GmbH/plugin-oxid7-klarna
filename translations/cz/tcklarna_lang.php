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

$sLangName = "Čeština";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Chyba při kontrole objednávky',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Sleva',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'podání',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Slevový kupón',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Dárkové balení',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Přání',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'příplatek za způsob platby',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Poplatek za ochranu kupujících od Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Heslo',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Ochrana kupujících od Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Jste již naším zákazníkem?',
    'TCKLARNA_LAW_NOTICE'                               => 'Platí podmínky <a href="%s" class="klarna-notification" target="_blank">používání</a> týkající se přenosu dat',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Máte poukaz?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'K pokladně',
    'TCKLARNA_BUY_NOW'                                  => 'Koupit nyní',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Použít jako dodací adresu',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Vyberte dodací adresu',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Vytvořit zákaznický účet',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Přihlásit se k odběru zpravodaje',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Vytvoření zákaznického účtu A přihlášení k odběru newsletteru',
    'TCKLARNA_NO_CHECKBOX'                              => 'Nezobrazovat zaškrtávací políčko',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Dodací adresa se může lišit od fakturační adresy',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Datum narození jako povinné pole',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Vyberte prosím zemi dodání:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Vaše země zde není uvedena?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Další země, do kterých dodáváme',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Moje země není v seznamu',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Ostatní země',
    'TCKLARNA_RESET_COUNTRY'                            => 'Vaše země: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'změnit',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Klikněte prosím na toto tlačítko, abyste zahájili přihlášení přes Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Pozor!</strong> Údaje v této objednávce se liší od údajů uložených u společnosti Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Objednávka byla zrušena. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Otevřít tuto objednávku v portálu Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Došlo k chybě. Zkuste to prosím znovu.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Chyba v konfiguraci – zkontrolujte adresy URL odkazující na obchodní podmínky a právo na odstoupení od smlouvy',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Chcete-li využít tento způsob platby přes Klarna, musí se jméno osoby a země v fakturační a dodací adrese shodovat.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Neplatný autorizační token. Zkuste to prosím znovu.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Údaje k objednávce se změnily.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Abyste mohli využít platební metodu Klarna, musí se zvolená měna shodovat s oficiální měnou země, ve které je vystavena faktura nebo do které je zásilka doručována.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Chyba konfigurace: V této zemi nejsou k dispozici žádné platební metody Klarna.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Platba touto platební metodou Klarna není v současné době k dispozici pro objednávky od firem.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Platba touto platební metodou Klarna je k dispozici pouze pro objednávky fyzických osob.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Platba touto platební metodou Klarna je v současné době k dispozici pouze pro objednávky od firem.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Prosím, souhlaste s obchodními podmínkami a podmínkami odstoupení od smlouvy pro digitální obsah.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Produkt „%s“ není dostatečně skladem.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'V současné době není pro tuto zemi definován žádný způsob dopravy: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Nejprve nakupte, pak zaplaťte',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Pohodlné splácení',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Platit snadno a přímo',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Hodnota objednávky je příliš vysoká.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Zvolený způsob platby: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Kreditní karta Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Inkaso Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna – okamžitý bankovní převod',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna – 3 splátky bez úroků',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Financování přes Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Faktura Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Faktura Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Platba kartou přes Klarna s 30denní splatností',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymizovaný název produktu:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Došlo k chybě. Obnovte stránku a zkuste to znovu.',
];
