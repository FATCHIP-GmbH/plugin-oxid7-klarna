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

$sLangName = "Norsk";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Feil ved gjennomgang av bestillingen',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Rabatt',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Serv',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Rabattkupong',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Gaveinnpakning',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Gratulasjonskort',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Tilleggsgebyr for betalingsmåte',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shops-avgift for kjøperbeskyttelse',

    'TCKLARNA_PASSWORD'                                 => 'Passord',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops kjøperbeskyttelse',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Er du allerede kunde?',
    'TCKLARNA_LAW_NOTICE'                               => '<a href="%s" class="klarna-notification" target="_blank">Vilkårene</a> for dataoverføring gjelder',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Har du en gavekort?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Gå til kassen',
    'TCKLARNA_BUY_NOW'                                  => 'Kjøp nå',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Bruk som leveringsadresse',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Velg leveringsadresse',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Opprett kundekonto',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Abonner på nyhetsbrevet',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Opprett kundekonto OG meld deg på nyhetsbrevet',
    'TCKLARNA_NO_CHECKBOX'                              => 'Ikke vis avkrysningsrute',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Leveringsadressen kan avvike fra fakturaadressen',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Fødselsdato som obligatorisk felt',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Vennligst velg leveringsland:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Er ikke landet ditt på listen?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Andre leveringsland',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Landet mitt står ikke på listen',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Andre land',
    'TCKLARNA_RESET_COUNTRY'                            => 'Landet ditt: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'endre',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Klikk på denne knappen for å starte påloggingen med Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Oppmerksomhet!</strong> Opplysningene i denne bestillingen avviker fra opplysningene som er lagret hos Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Bestillingen ble kansellert. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Åpne denne bestillingen i Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Det har oppstått en feil. Prøv igjen.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Feil i konfigurasjonen – sjekk nettadressene til vilkårene og angreretten',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'For å kunne bruke Klarna som betalingsmåte, må personen og landet i faktura- og leveringsadressen stemme overens.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Ugyldig autorisasjonstoken. Vennligst prøv på nytt.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Bestillingsopplysningene har endret seg.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'For å kunne bruke betalingsmåten Klarna, må den valgte valutaen samsvare med den offisielle valutaen i faktura- og leveringslandet ditt.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Konfigurasjonsfeil: Det finnes ingen Klarna-betalingsmåter i dette landet.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Betaling med denne Klarna-betalingsmåten er foreløpig ikke tilgjengelig for bestillinger fra bedrifter.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Betaling med denne Klarna-betalingsmåten er kun tilgjengelig for bestillinger fra privatpersoner.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Betaling med denne Klarna-betalingsmåten er foreløpig kun tilgjengelig for bestillinger fra bedrifter.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Vennligst godta vilkårene og betingelsene samt returrettbestemmelsene for digitalt innhold.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Det er ikke nok varer på lager av produktet «%s».',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'For øyeblikket er det ikke angitt noen fraktmetode for dette landet: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Kjøp først, betal senere',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Betal bekvemt i avdrag',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Betal enkelt og direkte',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Bestillingsbeløpet er for høyt.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Brukt betalingsmåte: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna-kredittkort',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna-direktebelastning',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna øyeblikkelig bankoverføring',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 rentefrie avdrag',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna-finansiering',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B-faktura (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna-faktura',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Klarna-kortbetaling med 30 dagers betalingsfrist',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymisert produkttittel:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Det har oppstått en feil. Oppdater siden og prøv på nytt.',
];
