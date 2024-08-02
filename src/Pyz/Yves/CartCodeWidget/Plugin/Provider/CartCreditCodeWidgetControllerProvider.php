<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CartCodeWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CartCodeWidget\Plugin\Router\CartCodeWidgetRouteProviderPlugin} instead.
 */
class CartCreditCodeWidgetControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const ROUTE_CART_POINT_ADD = 'cart-code/credit-code/add';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addAddPointRoute();
    }

    /**
     * @return $this
     */
    protected function addAddPointRoute()
    {
        $this->createController('/credit-code/credit-code/add', static::ROUTE_CART_POINT_ADD, 'CartCodeWidget', 'CreditCode', 'add')
            ->assert('cartCreditCode', $this->getAllowedLocalesPattern() . 'cart-code|cart-code')
            ->value('cartCreditCode', 'cart-point');

        return $this;
    }
}