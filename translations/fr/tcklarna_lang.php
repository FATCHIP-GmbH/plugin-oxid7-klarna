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

$sLangName = "Français";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Erreur lors de la vérification de la commande',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Réduction',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'service',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Bon de réduction',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Emballage cadeau',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Carte de vœux',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Supplément lié au mode de paiement',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Frais liés à la protection des acheteurs de Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Mot de passe',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Protection des acheteurs Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Vous êtes déjà client ?',
    'TCKLARNA_LAW_NOTICE'                               => 'Les <a href="%s" class="klarna-notification" target="_blank">conditions d\'utilisation</a> relatives au transfert de données s\'appliquent.',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Vous avez un bon d\'achat ?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Passer à la caisse',
    'TCKLARNA_BUY_NOW'                                  => 'Acheter maintenant',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Utiliser comme adresse de livraison',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Choisir l\'adresse de livraison',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Créer un compte client',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'S\'abonner à la newsletter',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Créer un compte client ET s\'abonner à la newsletter',
    'TCKLARNA_NO_CHECKBOX'                              => 'Ne pas afficher de case à cocher',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'L\'adresse de livraison peut être différente de l\'adresse de facturation',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'La date de naissance est un champ obligatoire',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Veuillez sélectionner votre pays de livraison :',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'Votre pays ne figure pas dans la liste ?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Autres pays de livraison',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Mon pays ne figure pas dans la liste',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Autres pays',
    'TCKLARNA_RESET_COUNTRY'                            => 'Votre pays : <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'modifier',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Veuillez cliquer sur ce bouton pour vous connecter via Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Attention !</strong> Les informations relatives à cette commande diffèrent de celles enregistrées chez Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'La commande a été annulée. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Accéder à cette commande sur le portail Klarna Merchant.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Une erreur s\'est produite. Veuillez réessayer.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Erreur de configuration - vérifiez les URL menant aux conditions générales de vente et à la politique de rétractation',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Pour utiliser ce mode de paiement Klarna, le nom et le pays indiqués dans l\'adresse de facturation doivent correspondre à ceux de l\'adresse de livraison.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Jeton d\'autorisation non valide. Veuillez réessayer.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Les informations relatives à la commande ont changé.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Pour pouvoir utiliser le mode de paiement Klarna, la devise sélectionnée doit correspondre à la devise officielle de votre pays de facturation/de livraison.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Erreur de configuration : aucun mode de paiement Klarna n\'est disponible dans ce pays.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Le paiement via Klarna n\'est actuellement pas disponible pour les commandes passées par des entreprises.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'Le paiement via Klarna n\'est disponible que pour les commandes passées par des particuliers.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'Le paiement via Klarna n\'est actuellement disponible que pour les commandes passées par des entreprises.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Veuillez accepter les conditions générales de vente et les conditions de rétractation relatives aux contenus numériques.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'Stock insuffisant pour le produit « %s ».',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Aucun mode de livraison n\'est actuellement défini pour ce pays : %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Acheter d\'abord, payer ensuite',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Payez en plusieurs fois, en toute simplicité',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Payer facilement et directement',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'Le montant de la commande est trop élevé.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Mode de paiement utilisé : ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Carte de crédit Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Prélèvement bancaire Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Virement bancaire instantané Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna : 3 versements sans intérêts',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Financement Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Facture Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Facture Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Paiement par carte Klarna à 30 jours',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Intitulé anonymisé du produit :',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Une erreur s\'est produite. Actualisez la page et réessayez.',
];
