<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CartCodeWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CartCreditCodeForm extends AbstractType
{
    /**
     * @var string
     */
    public const FORM_NAME = 'cartCreditCodeForm';

    /**
     * @var string
     */
    public const FIELD_CREDIT_POINT = 'point';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ENTER_CART_CREDIT = 'Enter credit points';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCodeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCodeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CREDIT_POINT, TextType::class, [
            'label' => static::GLOSSARY_KEY_ENTER_CART_CREDIT,
            'required' => false,
        ]);

        return $this;
    }
}