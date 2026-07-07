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

$sLangName = "Suomi";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Virhe tilauksen tarkistuksessa',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Alennus',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Syöttö',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Alennuskuponki',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Lahjapakkaus',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Onnittelukortti',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Maksutavan lisämaksu',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shopsin ostajansuojamaksu',

    'TCKLARNA_PASSWORD'                                 => 'Salasana',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops -ostajansuoja',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Oletko jo asiakas?',
    'TCKLARNA_LAW_NOTICE'                               => 'Tiedonsiirtoa koskevat <a href="%s" class="klarna-notification" target="_blank">käyttö</a>ehdot ovat voimassa',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Onko teillä lahjakortti?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Kassalle',
    'TCKLARNA_BUY_NOW'                                  => 'Osta nyt',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Käytä toimitusosoitteena',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Valitse toimitusosoite',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Luo asiakastili',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Tilaa uutiskirje',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Asiakastilin luominen JA uutiskirjeen tilaaminen',
    'TCKLARNA_NO_CHECKBOX'                              => 'Älä näytä valintaruutua',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Toimitusosoite voi poiketa laskutusosoitteesta',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Syntymäaika on pakollinen kenttä',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Valitse toimitusmaa:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Eikö maasi ole luettelossa?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Muut toimitusmaat',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Maani ei ole luettelossa',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Muut maat',
    'TCKLARNA_RESET_COUNTRY'                            => 'Maasi: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'muuttaa',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Napsauta tätä painiketta aloittaaksesi kirjautumisen Amazonin kautta',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Huomio!</strong> Tämän tilauksen tiedot poikkeavat Klarnassa tallennetuista tiedoista. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Tilaus on peruutettu. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Avaa tämä tilaus Klarna Merchant Portalissa.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Tapahtui virhe. Yritä uudelleen.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Määritysvirhe – tarkista käyttöehtojen ja peruuttamisoikeuden URL-osoitteet',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Jotta tätä Klarna-maksutapaa voidaan käyttää, laskutus- ja toimitusosoitteessa mainitun henkilön ja maan on vastattava toisiaan.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Valtuustunnus on virheellinen. Yritä uudelleen.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Tilaus tiedot ovat muuttuneet.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Jotta voit käyttää Klarna-maksutapaa, valitun valuutan on vastattava laskutus- tai toimitusmaasi virallista valuuttaa.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Määritysvirhe: Klarna-maksutapoja ei ole käytettävissä tässä maassa.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Tällä Klarna-maksutavalla maksaminen ei ole tällä hetkellä käytettävissä yritysten tilauksissa.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Tällä Klarna-maksutavalla maksaminen on mahdollista vain yksityishenkilöiden tilauksissa.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Maksaminen tällä Klarna-maksutavalla on tällä hetkellä käytettävissä vain yritysten tilauksille.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Hyväksythän digitaalisten sisältöjen käyttöehdot ja peruutusehdot.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Tuotteen ”%s” varastossa ei ole riittävästi varastossa.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Tällä hetkellä tälle maalle ei ole määritelty toimitustapaa: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Osta ensin, maksa sitten',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Maksaminen kätevästi erissä',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Maksaminen on helppoa ja suoraa',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Tilauksen arvo on liian suuri.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Käytetty maksutapa: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna-luottokortti',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna-suoraveloitus',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna-pikasiirto',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 korotonta erää',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna-rahoitus',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B -lasku (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna-lasku',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Klarna-korttimaksu 30 päivän kuluessa',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymisoitu tuotteen nimi:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Jotain meni pieleen. Päivitä sivu ja yritä uudelleen.',
];
