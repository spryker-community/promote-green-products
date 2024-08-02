<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CartCodeWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \Pyz\Yves\CartCodeWidget\CartCodeWidgetFactory getFactory()
 */
class CartCreditCodeFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('cartCreditCodeForm', $this->getCartCreditCodeFormView());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartCreditCodeFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartCodeWidget/views/cart-credit-code-form/cart-credit-code-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getCartCreditCodeFormView(): FormView
    {
        return $this->getFactory()
            ->getCartCreditCodeForm()
            ->createView();
    }
}