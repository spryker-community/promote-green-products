<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Communication\Plugin\Availability;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface;

/**
 * @method \Pyz\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 */
class FreeAvailability extends AbstractPlugin implements ShipmentMethodAvailabilityPluginInterface
{
    use LoggerTrait;
    
    /**
     * Specification:
     *  - Checks shipment method availability for shipment group.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isAvailable(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): bool
    {
        $isLowCarbonEmissionProductAdded = false;
        
        foreach($quoteTransfer->getItems() as $itemTransfer) {
            /**@var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $concreteAttributes = $itemTransfer->getConcreteAttributes();
            $isLowCarbonEmissionProductAdded = isset($concreteAttributes['co2']) && $concreteAttributes['co2'] > 0;
            if ($isLowCarbonEmissionProductAdded) {
                break;
            }
        }

        return $isLowCarbonEmissionProductAdded;
    }
}
