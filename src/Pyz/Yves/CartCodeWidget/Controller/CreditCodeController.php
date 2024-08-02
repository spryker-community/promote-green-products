<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CartCodeWidget\Controller;

use Pyz\Yves\CartCodeWidget\Form\CartCreditCodeForm;
use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Yves\CartCodeWidget\CartCodeWidgetFactory getFactory()
 */
class CreditCodeController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_POINT = 'point';

    /**
     * @var string
     */
    public const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->getCartCreditCodeForm()->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $points = (int)$form->get(CartCreditCodeForm::FIELD_CREDIT_POINT)->getData();

            $quoteTransfer = $this->getFactory()
                ->getQuoteClient()
                ->getQuote();

            $existingPoint = $quoteTransfer->getCustomerOrFail()->getGreenEnergyCredit();

            if ($existingPoint > $points) {
                $grandTotal = $quoteTransfer->getTotals()->getGrandTotal();
                $remainGrandTotal = $grandTotal - $points;
                $quoteTransfer->getTotals()->setGrandTotal($remainGrandTotal);
            }

            return $this->redirectResponseExternal($request->headers->get('referer'));
        }
    }
}