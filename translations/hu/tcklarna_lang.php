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

$sLangName = "Magyar";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Hiba a megrendelés ellenőrzése során',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Kedvezmény',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Szerva',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Kedvezményes utalvány',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Ajándékcsomagolás',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Üdvözlőkártya',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Fizetési mód utáni felár',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shops vásárlói védelmi díj',

    'TCKLARNA_PASSWORD'                                 => 'Jelszó',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops vásárlói védelem',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Már ügyfél?',
    'TCKLARNA_LAW_NOTICE'                               => 'Az adatátvitelre vonatkozó <a href="%s" class="klarna-notification" target="_blank">felhasználási</a> feltételek érvényesek',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Van utalványuk?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Pénztár',
    'TCKLARNA_BUY_NOW'                                  => 'Vásároljon most',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Szállítási címként használni',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Szállítási cím kiválasztása',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Fiók létrehozása',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Hírlevél feliratkozás',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Felhasználói fiók létrehozása ÉS hírlevélre való feliratkozás',
    'TCKLARNA_NO_CHECKBOX'                              => 'Ne jelenítse meg a jelölőnégyzetet',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'A szállítási cím eltérhet a számlázási címtől',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'A születési dátum kötelező mező',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Kérjük, válassza ki a szállítási országot:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Az Ön országa nem szerepel a listán?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Egyéb szállító országok',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Az én országom nincs a listán',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Egyéb országok',
    'TCKLARNA_RESET_COUNTRY'                            => 'Az Ön országa: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'módosítani',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Kérjük, kattintson erre a gombra az Amazon-fiókkal való bejelentkezés megkezdéséhez',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Figyelem!</strong> A megrendelés adatai eltérnek a Klarna rendszerében tárolt adatoktól. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'A megrendelést törölték. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Nyissa meg ezt a megrendelést a Klarna Merchant Portálon.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Hiba történt. Kérjük, próbálja meg újra.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Konfigurációs hiba – ellenőrizze az ÁSZF-re és az elállási jogra mutató URL-eket',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Ahhoz, hogy ezt a Klarna fizetési módot használhassa, a számlázási és a szállítási címben szereplő személynek és országnak meg kell egyeznie.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Érvénytelen engedélyezési token. Kérjük, próbálja meg újra.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'A rendelési adatok megváltoztak.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Ahhoz, hogy a Klarna fizetési módot használhassa, a kiválasztott pénznemnek meg kell egyeznie a számlázási/szállítási ország hivatalos pénznemével.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Konfigurációs hiba: Ebben az országban nincsenek elérhető Klarna fizetési módok.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'A Klarna fizetési mód használata jelenleg nem áll rendelkezésre vállalati megrendelések esetén.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'A Klarna fizetési móddal történő fizetés kizárólag magánszemélyek által leadott megrendelések esetén érhető el.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'A Klarna fizetési móddal történő fizetés jelenleg csak vállalati megrendelések esetén érhető el.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Kérjük, fogadja el a digitális tartalmakra vonatkozó általános szerződési feltételeket és az elállási feltételeket.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'A „%s” termék készlete nem elegendő.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Jelenleg nincs megadva szállítási mód ehhez az országhoz: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Először vásárolj, aztán fizess!',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Kényelmes részletfizetés',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Egyszerű és közvetlen fizetés',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'A rendelés értéke túl magas.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Használt fizetési mód: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna hitelkártya',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna beszedés',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna azonnali átutalás',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna 3 kamatmentes részlet',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna finanszírozás',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B számla (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna számla',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Klarna kártyás fizetés 30 napos fizetési határidővel',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonimizált termékcím:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Valami hiba történt. Frissítsd az oldalt, és próbáld meg újra.',
];
