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

$sLangName = "Dansk";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Fejl ved gennemgang af bestillingen',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Rabat',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Serv',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Rabatkupon',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Gaveindpakning',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Lykønskningskort',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Gebyr for betalingsmetode',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shops-gebyr for køberbeskyttelse',

    'TCKLARNA_PASSWORD'                                 => 'Adgangskode',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops-køberbeskyttelse',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Er du allerede kunde?',
    'TCKLARNA_LAW_NOTICE'                               => '<a href="%s" class="klarna-notification" target="_blank">Brugsbetingelserne</a> for dataoverførsel gælder',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Har du et gavekort?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Til kassen',
    'TCKLARNA_BUY_NOW'                                  => 'Køb nu',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Brug som leveringsadresse',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Vælg leveringsadresse',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Opret kundekonto',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Abonner på nyhedsbrevet',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Opret kundekonto OG tilmelding til nyhedsbrev',
    'TCKLARNA_NO_CHECKBOX'                              => 'Vis ikke afkrydsningsfeltet',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Leveringsadressen må afvige fra fakturaadressen',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Fødselsdato er et obligatorisk felt',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Vælg venligst dit leveringsland:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Er dit land ikke på listen?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Andre leveringslande',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Mit land er ikke på listen',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Andre lande',
    'TCKLARNA_RESET_COUNTRY'                            => 'Dit land: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'ændre',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Klik venligst på denne knap for at starte login med Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>OBS!</strong> Oplysningerne i denne bestilling afviger fra de oplysninger, der er gemt hos Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Bestillingen er blevet annulleret. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Åbn denne ordre i Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Der er opstået en fejl. Prøv venligst igen.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Fejl i konfigurationen – kontroller URL’erne til de generelle forretningsbetingelser og fortrydelsesretten',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'For at kunne benytte denne Klarna-betalingsmetode skal personen og landet i faktura- og leveringsadressen stemme overens.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Ugyldigt autorisationstoken. Prøv venligst igen.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Bestillingsoplysningerne er blevet ændret.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'For at kunne benytte betalingsmetoden Klarna skal den valgte valuta stemme overens med den officielle valuta i det land, hvor fakturaen skal udstedes, og hvor varen skal leveres.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Konfigurationsfejl: Der er ingen Klarna-betalingsmetoder tilgængelige i dette land.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Betaling med denne Klarna-betalingsmetode er i øjeblikket ikke tilgængelig for bestillinger fra virksomheder.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Betaling med denne Klarna-betalingsmetode er kun tilgængelig for bestillinger fra privatpersoner.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Betaling med denne Klarna-betalingsmetode er i øjeblikket kun tilgængelig for ordrer fra virksomheder.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Du bedes godkende de generelle forretningsbetingelser og fortrydelsesbetingelserne for digitalt indhold.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Der er ikke tilstrækkelige lagre af produktet »%s«.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Der er i øjeblikket ikke defineret nogen forsendelsesmetode for dette land: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Først køb, så betal',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Betal bekvemt i rater',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Betal nemt og direkte',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Ordreværdien er for høj.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Anvendt betalingsmetode: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna-kreditkort',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna-direkte debitering',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna Øjeblikkelig bankoverførsel',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna 3 rentefrie afdrag',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna-finansiering',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B-faktura (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna-faktura',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Klarna-kortbetaling med 30 dages betalingsfrist',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymiseret produktnavn:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Der er opstået en fejl. Genindlæs siden, og prøv igen.',
];
