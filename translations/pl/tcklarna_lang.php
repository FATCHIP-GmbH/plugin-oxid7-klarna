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

$sLangName = "Polski";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Błąd podczas sprawdzania zamówienia',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Zniżka',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Serwis',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Kupon rabatowy',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Opakowanie prezentowe',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Kartka z życzeniami',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Opłata za metodę płatności',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Opłata za ochronę kupujących w ramach programu Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Hasło',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Ochrona kupujących Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Jesteś już naszym klientem?',
    'TCKLARNA_LAW_NOTICE'                               => 'Obowiązują warunki <a href="%s" class="klarna-notification" target="_blank">korzystania</a> dotyczące przesyłania danych',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Mają Państwo kupon?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Do kasy',
    'TCKLARNA_BUY_NOW'                                  => 'Kup teraz',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Użyj jako adresu dostawy',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Wybierz adres dostawy',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Załóż konto klienta',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Zapisz się do newslettera',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Załóż konto klienta ORAZ zapisz się do newslettera',
    'TCKLARNA_NO_CHECKBOX'                              => 'Nie wyświetlaj pola wyboru',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Adres dostawy może różnić się od adresu rozliczeniowego',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Data urodzenia jako pole obowiązkowe',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Proszę wybrać kraj dostawy:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Nie ma Państwa kraju na liście?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Inne kraje dostawcze',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Mój kraj nie znajduje się na liście',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Inne kraje',
    'TCKLARNA_RESET_COUNTRY'                            => 'Twój kraj: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'zmienić',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Proszę kliknąć ten przycisk, aby rozpocząć logowanie za pomocą konta Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Uwaga!</strong> Dane dotyczące tego zamówienia różnią się od danych zapisanych w systemie Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Zamówienie zostało anulowane. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Wyświetl to zamówienie w portalu Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Wystąpił błąd. Spróbuj ponownie.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Błąd w konfiguracji – sprawdź adresy URL prowadzące do warunków handlowych i informacji o prawie do odstąpienia od umowy',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Aby skorzystać z tej metody płatności Klarna, dane osoby oraz kraj podane w adresie rozliczeniowym i adresie dostawy muszą być zgodne.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Nieprawidłowy token autoryzacyjny. Proszę spróbować ponownie.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Dane zamówienia uległy zmianie.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Aby móc skorzystać z metody płatności Klarna, wybrana waluta musi być zgodna z oficjalną walutą kraju, w którym wystawiona jest faktura lub do którego odbywa się dostawa.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Błąd konfiguracji: W tym kraju nie są dostępne metody płatności Klarna.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Płatność za pomocą tej metody płatności Klarna nie jest obecnie dostępna w przypadku zamówień składanych przez firmy.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Płatność za pomocą tej metody płatności Klarna jest dostępna wyłącznie w przypadku zamówień składanych przez osoby fizyczne.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Płatność za pomocą tej metody płatności Klarna jest obecnie dostępna wyłącznie dla zamówień składanych przez firmy.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Proszę wyrazić zgodę na Ogólne Warunki Handlowe oraz warunki odstąpienia od umowy dotyczące treści cyfrowych.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Brak wystarczających zapasów produktu „%s”.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Obecnie nie zdefiniowano żadnej metody wysyłki dla tego kraju: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Najpierw kup, potem zapłać',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Wygodna płatność w ratach',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Płać łatwo i bezpośrednio',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Wartość zamówienia jest zbyt wysoka.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Wybierana metoda płatności: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Karta kredytowa Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Pobranie przez Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna – natychmiastowy przelew',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna – 3 bezodsetkowe raty',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Finansowanie przez Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Faktura Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Faktura Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Płatność kartą Klarna z 30-dniowym terminem płatności',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonimizowany tytuł produktu:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Coś poszło nie tak. Odśwież stronę i spróbuj ponownie.',
];
