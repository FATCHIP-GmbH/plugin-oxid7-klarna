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

$sLangName = "Svenska";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Fel vid granskning av beställningen',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Rabatt',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Serv',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Rabattkupong',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Presentförpackning',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Gratulationskort',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Avgift för betalningssätt',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shops avgift för köparskydd',

    'TCKLARNA_PASSWORD'                                 => 'Lösenord',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops köparskydd',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Är du redan kund?',
    'TCKLARNA_LAW_NOTICE'                               => '<a href="%s" class="klarna-notification" target="_blank">Användarvillkoren</a> för dataöverföring gäller',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Har du ett presentkort?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Gå till kassan',
    'TCKLARNA_BUY_NOW'                                  => 'Köp nu',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Använd som leveransadress',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Välj leveransadress',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Skapa ett kundkonto',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Prenumerera på nyhetsbrevet',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Skapa ett kundkonto OCH prenumerera på nyhetsbrevet',
    'TCKLARNA_NO_CHECKBOX'                              => 'Visa ingen kryssruta',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Leveransadressen får skilja sig från fakturaadressen',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Födelsedatum är ett obligatoriskt fält',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Välj leveransland:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Finns ditt land inte med på listan?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Övriga leveransländer',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Mitt land finns inte med på listan',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Andra länder',
    'TCKLARNA_RESET_COUNTRY'                            => 'Ditt land: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'ändra',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Klicka på den här knappen för att inleda inloggningen med Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Obs!</strong> Uppgifterna för denna beställning skiljer sig från de uppgifter som finns lagrade hos Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Beställningen har annullerats. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Öppna den här beställningen i Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Ett fel har uppstått. Försök igen.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Fel i konfigurationen – kontrollera länkarna till användarvillkoren och ångerrätten',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'För att kunna använda betalningssättet Klarna måste personen och landet i faktura- och leveransadressen stämma överens.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Ogiltig auktoriseringstoken. Försök igen.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Beställningsuppgifterna har ändrats.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'För att kunna använda betalningssättet Klarna måste den valda valutan stämma överens med den officiella valutan i det land där fakturan ska utfärdas respektive leveransen ska ske.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Konfigurationsfel: Det finns inga Klarna-betalningsalternativ tillgängliga i detta land.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Betalning med denna Klarna-betalningsmetod är för närvarande inte tillgänglig för beställningar från företag.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Betalning med denna Klarna-betalningsmetod är endast tillgänglig för beställningar från privatpersoner.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Betalning med denna Klarna-betalningsmetod är för närvarande endast tillgänglig för beställningar från företag.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Vänligen godkänn användarvillkoren och ångerrätten för digitalt innehåll.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Det finns inte tillräckligt med lager av produkten ”%s”.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'För närvarande finns inget leveranssätt angivet för detta land: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Köp först, betala sedan',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Betala bekvämt i delbetalningar',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Betala enkelt och direkt',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Ordervärdet är för högt.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Använd betalningsmetod: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna-kreditkort',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna direktdebitering',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna direktöverföring',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna – 3 räntefria delbetalningar',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna-finansiering',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B-faktura (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna-faktura',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Klarna-kortbetalning med 30 dagars betalningstid',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymiserad produkttitel:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Något har gått fel. Ladda om sidan och försök igen.',
];
