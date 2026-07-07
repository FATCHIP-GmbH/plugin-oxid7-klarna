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

$sLangName = "Português";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Erro na verificação da encomenda',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Desconto',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Saque',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Cupão de desconto',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Embalagem de presente',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Cartão de felicitações',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Sobretaxa por forma de pagamento',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Taxa de proteção ao comprador da Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Palavra-passe',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Proteção ao Consumidor da Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => 'Já é cliente?',
    'TCKLARNA_LAW_NOTICE'                               => 'Aplicam-se as <a href="%s" class="klarna-notification" target="_blank">condições de utilização</a> relativas à transmissão de dados',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => 'Tem um vale-presente?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Ir para a caixa',
    'TCKLARNA_BUY_NOW'                                  => 'Comprar agora',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Utilizar como morada de entrega',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Escolher a morada de entrega',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Criar conta de cliente',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Subscrever a newsletter',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Criar conta de cliente E subscrever a newsletter',
    'TCKLARNA_NO_CHECKBOX'                              => 'Não apresentar a caixa de seleção',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'A morada de entrega pode ser diferente da morada de faturação',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'Data de nascimento como campo obrigatório',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Por favor, selecione o país de destino:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => 'O seu país não consta da lista?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Outros países de origem',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'O meu país não consta da lista',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Outros países',
    'TCKLARNA_RESET_COUNTRY'                            => 'O seu país: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'alterar',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Por favor, clique neste botão para iniciar o início de sessão com a Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>Atenção!</strong> Os dados desta encomenda diferem dos dados registados na Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'A encomenda foi cancelada. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Aceder a esta encomenda no Portal do Comerciante da Klarna.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Ocorreu um erro. Por favor, tente novamente.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Erro na configuração — verifique os URLs dos Termos e Condições Gerais e do Direito de Rescisão',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Para utilizar esta forma de pagamento Klarna, o nome da pessoa e o país indicados na morada de faturação e na morada de entrega têm de coincidir.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Token de autorização inválido. Por favor, tente novamente.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Os dados da encomenda foram alterados.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Para poder utilizar a forma de pagamento Klarna, a moeda selecionada deve corresponder à moeda oficial do seu país de faturação/entrega.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Erro de configuração: Neste país, não estão disponíveis formas de pagamento Klarna.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'O pagamento através deste método de pagamento da Klarna não está, de momento, disponível para encomendas feitas por empresas.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'O pagamento através deste método de pagamento da Klarna só está disponível para encomendas efetuadas por particulares.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'O pagamento através deste método de pagamento da Klarna está, de momento, disponível apenas para encomendas feitas por empresas.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Por favor, aceite os Termos e Condições Gerais e as condições de rescisão relativas a conteúdos digitais.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'O stock do produto «%s» não é suficiente.',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'De momento, não está definida nenhuma modalidade de envio para este país: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Primeiro comprar, depois pagar',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Pague comodamente em prestações',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Pagar de forma simples e direta',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'O valor da encomenda é demasiado elevado.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Forma de pagamento utilizada: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Cartão de crédito Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Débito direto Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Transferência imediata Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 prestações sem juros',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Financiamento Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Fatura Klarna B2B (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Fatura Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Pagamento com cartão Klarna em 30 dias',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Título do produto anonimizado:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Ocorreu um erro. Atualiza a página e tenta novamente.',
];
