<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CartCodeWidget;

use Pyz\Yves\CartCodeWidget\Form\CartCreditCodeForm;
use Symfony\Component\Form\FormInterface;
use SprykerShop\Yves\CartCodeWidget\CartCodeWidgetFactory as SprykerCartCodeWidgetFactory;

class CartCodeWidgetFactory extends SprykerCartCodeWidgetFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartCreditCodeForm(): FormInterface
    {
        return $this->getFormFactory()->create(CartCreditCodeForm::class);
    }
}