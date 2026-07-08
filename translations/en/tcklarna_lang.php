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

$sLangName = "English";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Error whilst checking the order',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Discount',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Serve',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Voucher discount',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Gift wrapping',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Greeting card',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Payment method surcharge',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Trusted Shops Buyer Protection Fee',

    'TCKLARNA_PASSWORD'                                 => 'Password',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Trusted Shops Buyer Protection',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Already a customer?',
    'TCKLARNA_LAW_NOTICE'                               => 'The <a href="%s" class="klarna-notification" target="_blank">Terms and Conditions</a> for Data Transmission apply',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Do you have a voucher?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Proceed to checkout',
    'TCKLARNA_BUY_NOW'                                  => 'Buy now',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Use as delivery address',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Select delivery address',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Create a customer account',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Subscribe to the newsletter',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Create a customer account AND subscribe to the newsletter',
    'TCKLARNA_NO_CHECKBOX'                              => 'Do not display the tick box',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'The delivery address may differ from the billing address',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Date of birth as a required field',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Please select your delivery country:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Is your country not on the list?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Other countries of supply',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'My country isn\'t on the list',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Other countries',
    'TCKLARNA_RESET_COUNTRY'                            => 'Your country: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'change',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Please click on this button to start logging in with Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Please note!</strong> The details of this order differ from those held by Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'The order has been cancelled. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">View this order in the Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'An error has occurred. Please try again.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Configuration error – please check the URLs for the Terms and Conditions and the right of withdrawal',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'To use this Klarna payment method, the name and country must match in both the billing and delivery addresses.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Invalid authorisation token. Please try again.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'The order details have changed.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'To use the Klarna payment method, the selected currency must match the official currency of your billing/delivery country.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Configuration error: No Klarna payment methods are available in this country.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Payment via this Klarna payment method is currently not available for orders placed by businesses.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Payment via this Klarna payment method is only available for orders placed by private individuals.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Payment via this Klarna payment method is currently only available for orders placed by businesses.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Please accept the Terms and Conditions and the Cancellation Policy for digital content.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => '%s is currently out of stock.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'No delivery method has currently been defined for this country: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Buy first, pay later',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Pay in convenient instalments',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Pay easily and directly',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'The order value is too high.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Payment method used: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Klarna credit card',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Klarna Direct Debit',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna Instant Bank Transfer',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 interest-free instalments',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Klarna Financing',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Klarna B2B Invoice (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Klarna Invoice',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Klarna card payment in 30 days',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Anonymised product title:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Something has gone wrong. Please refresh the page and try again.',
];
