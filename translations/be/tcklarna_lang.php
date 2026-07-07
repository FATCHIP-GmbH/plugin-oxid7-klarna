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

$sLangName = "Nederlands";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Fout bij het controleren van de bestelling',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Korting',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Service',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Kortingsbon',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Cadeauverpakking',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Wenskaart',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Toeslag voor bepaalde betaalwijzen',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shops-vergoeding voor kopersbescherming',

    'TCKLARNA_PASSWORD'                                 => 'Wachtwoord',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops-kopersbescherming',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Bent u al klant?',
    'TCKLARNA_LAW_NOTICE'                               => 'De <a href="%s" class="klarna-notification" target="_blank">gebruiksvoorwaarden</a> voor gegevensoverdracht zijn van toepassing',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Heeft u een cadeaubon?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Naar de kassa',
    'TCKLARNA_BUY_NOW'                                  => 'Nu kopen',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Als afleveradres gebruiken',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Leveringsadres kiezen',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Een klantenaccount aanmaken',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Aanmelden voor  nieuwsbrief',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Een klantaccount aanmaken EN je aanmelden voor de nieuwsbrief',
    'TCKLARNA_NO_CHECKBOX'                              => 'Geen selectievakje weergeven',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Het afleveradres mag afwijken van het factuuradres',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Geboortedatum is een verplicht veld',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Kies uw land van levering:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Staat uw land er niet bij?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Meer leveringslanden',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Mijn land staat niet in de lijst',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Andere landen',
    'TCKLARNA_RESET_COUNTRY'                            => 'Uw land: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'wijzigen',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Klik op deze knop om in te loggen via Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Let op!</strong> De gegevens van deze bestelling wijken af van de gegevens die bij Klarna zijn opgeslagen. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'De bestelling is geannuleerd. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Deze bestelling in het Klarna Merchant Portal openen.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Er is een fout opgetreden. Probeer het nog eens.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Fout in de configuratie – controleer de URL\'s naar de algemene voorwaarden en het herroepingsrecht',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Om deze Klarna-betaalmethode te kunnen gebruiken, moeten de naam en het land in het factuur- en afleveradres overeenkomen.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Ongeldig autorisatietoken. Probeer het nog eens.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'De bestelgegevens zijn gewijzigd.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Om gebruik te kunnen maken van de betaalmethode Klarna, moet de gekozen valuta overeenkomen met de officiële valuta van het land waar de factuur naartoe gaat of waar de levering plaatsvindt.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Configuratiefout: in dit land zijn er geen Klarna-betaalmethoden beschikbaar.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Betalen via deze Klarna-betaalmethode is momenteel niet beschikbaar voor bestellingen van bedrijven.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Betalen via deze Klarna-betaalmethode is alleen mogelijk voor bestellingen van particulieren.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Betalen via deze Klarna-betaalmethode is momenteel alleen beschikbaar voor bestellingen van bedrijven.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Ga alstublieft akkoord met de algemene voorwaarden en de herroepingsvoorwaarden voor digitale inhoud.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Onvoldoende voorraad van het product %s.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Er is momenteel geen verzendmethode gedefinieerd voor dit land: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Eerst kopen, dan betalen',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Gemakkelijk in termijnen betalen',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Eenvoudig en direct betalen',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Het bestelbedrag is te hoog.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Gebruikte betaalmethode: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna-creditcard',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna-incasso',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna Directe overschrijving',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 rentevrije termijnen',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna-financiering',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B-factuur (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna-factuur',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Betaling met Klarna-kaart binnen 30 dagen',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Geanonimiseerde producttitel:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Er is iets misgegaan. Laad de pagina opnieuw en probeer het nog eens.',
];
