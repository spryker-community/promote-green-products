<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Customer\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Pyz\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class GreenEnergyCreditCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $totalCreditPoints = 0;
        $totalCarbonEmission = 0;
        $customerEntity = $orderEntity->getCustomer();
        
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($customerEntity->getIdCustomer());
        $customerTransfer->setGreenEnergyCredit($customerEntity->getGreenEnergyCredit());
        
        foreach($orderItems as $item) {
            $productConcreteTransfer = $this->getFactory()->getProductFacade()->getProductConcrete($item->getSku());
            $attributes = $productConcreteTransfer->getAttributes();
            if (isset($attributes['co2']) && $attributes['co2'] != '') {
                $totalCarbonEmission += $attributes['co2'];
            }
        }

        if ($totalCarbonEmission > 0 && $totalCarbonEmission <= 100) {
            $orderCreditPercentage = 10;
        } elseif ($totalCarbonEmission > 100 && $totalCarbonEmission <= 200) {
            $orderCreditPercentage = 5;
        } elseif ($totalCarbonEmission > 200 && $totalCarbonEmission <= 300) {
            $orderCreditPercentage = 2;
        } else {
            $orderCreditPercentage = 0;
        }

        $salesOrderTotalsEntity = $orderEntity->getLastOrderTotals();
        $totalCreditPoints = (int)((($salesOrderTotalsEntity->getGrandTotal() * $orderCreditPercentage)/100)/100);
        $updateCreditPoint = $customerTransfer->getGreenEnergyCredit() + $totalCreditPoints;
        $customerTransfer->setGreenEnergyCredit($updateCreditPoint);
        $this->getFacade()->updateCustomer($customerTransfer);

        return [];
    }
}