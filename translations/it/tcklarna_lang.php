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

$sLangName = "Italiano";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Errore durante la verifica dell\'ordine',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Sconto',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Servizio',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Buono sconto',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Confezione regalo',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Biglietto di auguri',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Supplemento per modalità di pagamento',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Commissione per la protezione dell\'acquirente di Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Password',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Protezione dell\'acquirente di Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Sei già cliente?',
    'TCKLARNA_LAW_NOTICE'                               => 'Si applicano le <a href="%s" class="klarna-notification" target="_blank">condizioni d\'uso</a> relative al trasferimento dei dati',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Ha un buono?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Vai alla cassa',
    'TCKLARNA_BUY_NOW'                                  => 'Acquista ora',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Utilizza come indirizzo di consegna',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Seleziona l\'indirizzo di consegna',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Crea un account cliente',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Iscriviti alla newsletter',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Crea un account cliente E iscriviti alla newsletter',
    'TCKLARNA_NO_CHECKBOX'                              => 'Non visualizzare la casella di selezione',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'L\'indirizzo di consegna può essere diverso dall\'indirizzo di fatturazione',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Data di nascita come campo obbligatorio',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Selezionare il Paese di destinazione:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Il vostro Paese non è nell\'elenco?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Altri paesi di provenienza',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Il mio Paese non è presente nell\'elenco',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Altri paesi',
    'TCKLARNA_RESET_COUNTRY'                            => 'Il vostro Paese: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'modificare',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Clicca su questo pulsante per avviare l\'accesso tramite Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Attenzione!</strong> I dati di questo ordine non corrispondono a quelli registrati presso Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'L\'ordine è stato annullato. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Accedi a questo ordine nel Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Si è verificato un errore. Riprovare.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Errore di configurazione: verificare gli URL relativi alle Condizioni generali di contratto e al diritto di recesso',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Per poter utilizzare questo metodo di pagamento Klarna, il nome della persona e il Paese indicati nell\'indirizzo di fatturazione e in quello di consegna devono corrispondere.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Token di autorizzazione non valido. Si prega di riprovare.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'I dati dell\'ordine sono cambiati.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Per poter utilizzare il metodo di pagamento Klarna, la valuta selezionata deve corrispondere alla valuta ufficiale del Paese di fatturazione/consegna.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Errore di configurazione: in questo Paese non sono disponibili metodi di pagamento Klarna.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Il pagamento tramite questo metodo di pagamento Klarna non è attualmente disponibile per gli ordini effettuati da aziende.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Il pagamento con questo metodo di pagamento Klarna è disponibile solo per gli ordini effettuati da privati.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Il pagamento tramite questo metodo di pagamento Klarna è attualmente disponibile solo per gli ordini effettuati da aziende.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Si prega di accettare le Condizioni generali di contratto e le condizioni di recesso relative ai contenuti digitali.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Scorte insufficienti del prodotto "%s".',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Al momento non è stata definita alcuna modalità di spedizione per questo Paese: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Prima acquista, poi paga',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Paga comodamente a rate',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Pagare in modo semplice e diretto',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Il valore dell\'ordine è troppo alto.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Metodo di pagamento utilizzato: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Carta di credito Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Addebito diretto Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna - Bonifico immediato',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 rate senza interessi',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Finanziamento Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Fattura Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Fattura Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Pagamento con carta Klarna in 30 giorni',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Titolo del prodotto in forma anonima:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Si è verificato un errore. Aggiorna la pagina e riprova.',
];
