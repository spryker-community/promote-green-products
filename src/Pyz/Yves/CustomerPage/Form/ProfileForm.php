<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use SprykerShop\Yves\CustomerPage\Form\ProfileForm as SprykerProfileForm;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class ProfileForm extends SprykerProfileForm
{
    /**
     * @var string
     */
    public const FIELD_CREDIT = 'green_energy_credit';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $this
            ->addCreditField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addCreditField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CREDIT, TextType::class, [
            'label' => 'Green Energy Credit',
            'required' => true,
            'disabled' => true,
            'required' => false,
            'sanitize_xss' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createEmailConstraint(),
            ],
        ]);

        return $this;
    }
}