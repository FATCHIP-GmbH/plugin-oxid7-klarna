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

$sLangName = "Ελληνικά";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Σφάλμα κατά τον έλεγχο της παραγγελίας',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Έκπτωση',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Σερβίς',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Κουπόνι έκπτωσης',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Συσκευασία δώρου',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Ευχετήρια κάρτα',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Επιπλέον χρέωση ανάλογα με τον τρόπο πληρωμής',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Τέλος για την προστασία αγοραστών της Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Κωδικός πρόσβασης',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Προστασία αγοραστών από την Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Είστε ήδη πελάτης;',
    'TCKLARNA_LAW_NOTICE'                               => 'Ισχύουν οι <a href="%s" class="klarna-notification" target="_blank">όροι χρήσης</a> σχετικά με τη μεταφορά δεδομένων',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Έχετε κάποιο κουπόνι;',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Προς το ταμείο',
    'TCKLARNA_BUY_NOW'                                  => 'Αγοράστε τώρα',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Να χρησιμοποιηθεί ως διεύθυνση παράδοσης',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Επιλογή διεύθυνσης παράδοσης',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Δημιουργία λογαριασμού πελάτη',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Εγγραφή στο ενημερωτικό δελτίο',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Δημιουργία λογαριασμού πελάτη ΚΑΙ εγγραφή στο ενημερωτικό δελτίο',
    'TCKLARNA_NO_CHECKBOX'                              => 'Να μην εμφανίζεται το πλαίσιο επιλογής',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'Η διεύθυνση παράδοσης μπορεί να διαφέρει από τη διεύθυνση τιμολόγησης',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Η ημερομηνία γέννησης είναι υποχρεωτικό πεδίο',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Παρακαλώ επιλέξτε τη χώρα παράδοσης:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Δεν περιλαμβάνεται η χώρα σας στη λίστα;',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Άλλες χώρες προέλευσης',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Η χώρα μου δεν περιλαμβάνεται στη λίστα',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Άλλες χώρες',
    'TCKLARNA_RESET_COUNTRY'                            => 'Η χώρα σας: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'αλλαγή',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Παρακαλώ κάντε κλικ σε αυτό το κουμπί για να ξεκινήσετε τη σύνδεση μέσω Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Προσοχή!</strong> Τα στοιχεία αυτής της παραγγελίας διαφέρουν από τα στοιχεία που είναι καταχωρημένα στην Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'Η παραγγελία ακυρώθηκε. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Προβάλετε αυτήν την παραγγελία στο Klarna Merchant Portal.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Παρουσιάστηκε σφάλμα. Παρακαλώ, δοκιμάστε ξανά.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Σφάλμα στη διαμόρφωση – ελέγξτε τις διευθύνσεις URL που οδηγούν στους Όρους Χρήσης και στο Δικαίωμα Υπαναχώρησης',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Για να χρησιμοποιήσετε αυτόν τον τρόπο πληρωμής μέσω Klarna, το όνομα και η χώρα που αναγράφονται στη διεύθυνση χρέωσης και στη διεύθυνση παράδοσης πρέπει να συμπίπτουν.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Μη έγκυρο διακριτικό εξουσιοδότησης. Παρακαλώ δοκιμάστε ξανά.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Τα στοιχεία της παραγγελίας έχουν αλλάξει.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Για να μπορέσετε να χρησιμοποιήσετε τον τρόπο πληρωμής Klarna, το νόμισμα που έχετε επιλέξει πρέπει να ταιριάζει με το επίσημο νόμισμα της χώρας τιμολόγησης/παράδοσης.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Σφάλμα διαμόρφωσης: Σε αυτή τη χώρα δεν διατίθενται τρόποι πληρωμής μέσω Klarna.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Η πληρωμή μέσω αυτής της μεθόδου πληρωμής της Klarna δεν είναι προς το παρόν διαθέσιμη για παραγγελίες από εταιρείες.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Η πληρωμή με αυτόν τον τρόπο πληρωμής της Klarna είναι διαθέσιμη μόνο για παραγγελίες από ιδιώτες.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Η πληρωμή μέσω αυτής της μεθόδου πληρωμής της Klarna είναι προς το παρόν διαθέσιμη μόνο για παραγγελίες από εταιρείες.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Παρακαλούμε να αποδεχτείτε τους Όρους Χρήσης και τους Όρους Υπαναχώρησης για ψηφιακά περιεχόμενα.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Δεν υπάρχουν επαρκή αποθέματα του προϊόντος «%s».',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Προς το παρόν δεν έχει οριστεί κανένας τρόπος αποστολής για αυτή τη χώρα: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Πρώτα αγοράζεις, μετά πληρώνεις',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Πληρώστε άνετα με δόσεις',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Πληρώστε εύκολα και άμεσα',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Το ποσό της παραγγελίας είναι πολύ υψηλό.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Τρόπος πληρωμής που χρησιμοποιήθηκε: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Πιστωτική κάρτα Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Αυτόματη χρέωση Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Άμεση μεταφορά χρημάτων μέσω Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 δόσεις χωρίς τόκους',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Χρηματοδότηση μέσω Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Τιμολόγιο Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Τιμολόγιο Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Πληρωμή με κάρτα Klarna σε 30 ημέρες',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Ανώνυμος τίτλος προϊόντος:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Κάτι πήγε στραβά. Ανανέωσε τη σελίδα και δοκίμασε ξανά.',
];
