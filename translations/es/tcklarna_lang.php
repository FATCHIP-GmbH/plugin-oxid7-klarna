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

$sLangName = "Español";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = [
    'charset'                                           => 'UTF-8',

    'TCKLARNA_EXCEPTION_OUT_OF_STOCK'                   => 'Error al comprobar el pedido',

    'TCKLARNA_DISCOUNT_TITLE'                           => 'Descuento',
    'TCKLARNA_SURCHARGE_TITLE'                          => 'Saque',
    'TCKLARNA_VOUCHER_DISCOUNT'                         => 'Cupón de descuento',
    'TCKLARNA_GIFT_WRAPPING_TITLE'                      => 'Envase de regalo',
    'TCKLARNA_GIFT_CARD_TITLE'                          => 'Tarjeta de felicitación',
    'TCKLARNA_PAYMENT_FEE_TITLE'                        => 'Recargo por forma de pago',
    'TCKLARNA_TRUSTED_SHOPS_EXCELLENCE_FEE_TITLE'       => 'Cuota por la protección al comprador de Trusted Shops',

    'TCKLARNA_PASSWORD'                                 => 'Contraseña',
    'TCKLARNA_TRUSTED_SHOP_BUYER_PROTECTION'            => 'Protección al comprador de Trusted Shops',
    'TCKLARNA_ALREADY_A_CUSTOMER'                       => '¿Ya eres cliente?',
    'TCKLARNA_LAW_NOTICE'                               => 'Se aplican las <a href="%s" class="klarna-notification" target="_blank">condiciones de uso</a> relativas a la transmisión de datos',
    'TCKLARNA_OUTSIDE_VOUCHER'                          => '¿Tienes un vale?',
    'TCKLARNA_GO_TO_CHECKOUT'                           => 'Ir a la caja',
    'TCKLARNA_BUY_NOW'                                  => 'Comprar ahora',
    'TCKLARNA_USE_AS_DELIVERY_ADDRESS'                  => 'Utilizar como dirección de entrega',
    'TCKLARNA_CHOOSE_DELIVERY_ADDRESS'                  => 'Seleccionar dirección de entrega',
    'TCKLARNA_CREATE_USER_ACCOUNT'                      => 'Crear una cuenta de cliente',
    'TCKLARNA_SUBSCRIBE_TO_NEWSLETTER'                  => 'Suscríbete al boletín informativo',
    'TCKLARNA_CREATE_USER_ACCOUNT_AND_SUBSCRIBE'        => 'Crear una cuenta de cliente Y suscribirse al boletín informativo',
    'TCKLARNA_NO_CHECKBOX'                              => 'No mostrar la casilla de selección',
    'TCKLARNA_ALLOW_SEPARATE_SHIPPING_ADDRESS'          => 'La dirección de entrega puede ser diferente de la dirección de facturación',
    'TCKLARNA_DATE_OF_BIRTH_MANDATORY'                  => 'La fecha de nacimiento es un campo obligatorio',
    'TCKLARNA_CHOOSE_YOUR_SHIPPING_COUNTRY'             => 'Seleccione el país de destino:',
    'TCKLARNA_CHOOSE_YOUR_NOT_SUPPORTED_COUNTRY'        => '¿No aparece su país en la lista?',
    'TCKLARNA_MORE_COUNTRIES'                           => 'Otros países de destino',
    'TCKLARNA_MY_COUNTRY_IS_NOT_LISTED'                 => 'Mi país no aparece en la lista',
    'TCKLARNA_OTHER_COUNTRY'                            => 'Otros países',
    'TCKLARNA_RESET_COUNTRY'                            => 'Su país: <strong>%s</strong> ',
    'TCKLARNA_CHANGE_COUNTRY'                           => 'modificar',
    'TCKLARNA_LOGIN_INTO_AMAZON'                        => 'Haz clic en este botón para iniciar el inicio de sesión con Amazon',
    'KLARNA_ORDER_NOT_IN_SYNC'                          => '<strong>¡Atención!</strong> Los datos de este pedido difieren de los que figuran en Klarna. ',
    'KLARNA_ORDER_IS_CANCELLED'                         => 'El pedido se ha cancelado. ',
    'KLARNA_SEE_ORDER_IN_PORTAL'                        => '<a href="%s" target="_blank" class="alert-link">Accede a este pedido en el portal para comerciantes de Klarna.</a>',
    'KLARNA_WENT_WRONG_TRY_AGAIN'                       => 'Se ha producido un error. Inténtalo de nuevo.',
    'KLARNA_WRONG_URLS_CONFIG'                          => 'Error en la configuración: compruebe las URL de las condiciones generales y el derecho de desistimiento',
    'TCKLARNA_KP_MATCH_ERROR'                           => 'Para utilizar esta forma de pago de Klarna, el nombre de la persona y el país deben coincidir en la dirección de facturación y en la de entrega.',
    'TCKLARNA_KP_INVALID_TOKEN'                         => 'Token de autorización no válido. Inténtalo de nuevo.',
    'TCKLARNA_KP_ORDER_DATA_CHANGED'                    => 'Los datos del pedido han cambiado.',
    'TCKLARNA_KP_CURRENCY_DONT_MATCH'                   => 'Para poder utilizar la forma de pago Klarna, la moneda seleccionada debe coincidir con la moneda oficial de tu país de facturación o de entrega.',
    'TCKLARNA_KP_NOT_KLARNA_CORE_COUNTRY'               => 'Error de configuración: En este país no hay métodos de pago de Klarna disponibles.',

    'KP_NOT_AVAILABLE_FOR_COMPANIES'                    => 'Actualmente, el pago mediante este método de pago de Klarna no está disponible para pedidos de empresas.',
    'KP_AVAILABLE_FOR_PRIVATE_ONLY'                     => 'El pago mediante este método de pago de Klarna solo está disponible para pedidos realizados por particulares.',
    'KP_AVAILABLE_FOR_COMPANIES_ONLY'                   => 'El pago mediante este método de pago de Klarna solo está disponible actualmente para pedidos de empresas.',
    'TCKLARNA_PLEASE_AGREE_TO_TERMS'                    => 'Por favor, acepte las condiciones generales de contratación y las condiciones de desistimiento para contenidos digitales.',
    'TCKLARNA_ERROR_NOT_ENOUGH_IN_STOCK'                => 'No hay existencias suficientes del producto «%s».',
    'TCKLARNA_ERROR_NO_SHIPPING_METHODS_SET_UP'         => 'Actualmente no hay ningún método de envío definido para este país: %s',

    'TCKLARNA_PAY_LATER_SUBTITLE'                       => 'Primero compra, luego paga',
    'TCKLARNA_SLICE_IT_SUBTITLE'                        => 'Paga cómodamente a plazos',
    'TCKLARNA_PAY_NOW_SUBTITLE'                         => 'Pagar de forma sencilla y directa',
    'TCKLARNA_ORDER_AMOUNT_TOO_HIGH'                    => 'El importe del pedido es demasiado elevado.',

    'TCKLARNA_AUTHPAYMENTMETHOD'                        => 'Forma de pago utilizada: ',
    'TCKLARNA_AUTHPAYMENTMETHOD_unknown'                => 'Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_by_card'            => 'Tarjeta de crédito Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_debit'           => 'Débito directo de Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_direct_bank_transfer'   => 'Klarna: transferencia inmediata',
    'TCKLARNA_AUTHPAYMENTMETHOD_slice_it_by_card'       => 'Klarna: 3 cuotas sin intereses',
    'TCKLARNA_AUTHPAYMENTMETHOD_fixed_sum_credit'       => 'Financiación de Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_b2b_invoice'            => 'Factura B2B de Klarna (Billie)',
    'TCKLARNA_AUTHPAYMENTMETHOD_invoice'                => 'Factura de Klarna',
    'TCKLARNA_AUTHPAYMENTMETHOD_pay_later_by_card'      => 'Pago con tarjeta Klarna a 30 días',

    'TCKLARNA_ANONYMIZED_PRODUCT'                       => 'Título del producto anonimizado:',

    'TCKLARNA_IS_ERROR_DEFAULT'                         => 'Ha habido un error. Actualiza la página e inténtalo de nuevo.',
];
