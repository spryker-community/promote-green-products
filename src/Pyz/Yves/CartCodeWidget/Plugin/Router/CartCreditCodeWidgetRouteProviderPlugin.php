<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CartCodeWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CartCreditCodeWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CartCodeWidget\Plugin\Router\CartCodeWidgetRouteProviderPlugin::ROUTE_NAME_CART_CODE_ADD} instead.
     *
     * @var string
     */
    protected const ROUTE_CART_CODE_ADD = 'cart-code/credit-code/add';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_POINT_ADD = 'cart-code/credit-code/add';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAddCreditPointRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddCreditPointRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-code/credit-code/add', 'CartCodeWidget', 'CreditCode', 'addAction');
        $routeCollection->add(static::ROUTE_NAME_CART_POINT_ADD, $route);

        return $routeCollection;
    }
}