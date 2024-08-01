<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractIdTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\Shipment\ShipmentConfig;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 */
class ShipmentDetailsPlugin extends AbstractPlugin
{
    use LoggerTrait;

    /**
     * @var object
     */
    protected $quoteTransfer;

    /**
     * @var string
     */
    protected $shippingMethod;

    /**
     * @var string
     */
    protected $dealerFreightDetail;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->quoteTransfer = $quoteTransfer;
    }

    /**
     * Specification:
     *  - Returns shipment method price for shipment group.
     *
     * @api
     *
     * @param string $methodName
     *
     * @return int
     */
    public function getPrice($methodName): int
    {
        $this->shippingMethod = $methodName;
        $totalWeight = $totalVolume = 0;
        foreach ($this->quoteTransfer->getItems() as $item) {
            [$weight, $volume] = $this->getDeadWeightAndCubicWeight($item);
            $totalWeight += $weight;
            $totalVolume += $volume;
        }

        $quoteFirstItem = $this->quoteTransfer->getItems()->getIterator()->current() ?? null;
        $merchantReference = $quoteFirstItem->getMerchantReference();

        if (($totalWeight >= $totalVolume) || ($totalWeight == 0 && $totalVolume == 0)) {
            $cost = $this->getFacade()->getShippingMethodCostByWeight(
                $merchantReference,
                $this->shippingMethod,
                $totalWeight,
                $this->quoteTransfer->getAddressDistance(),
            );
        } else {
            $cost = $this->getFacade()->getShippingMethodCostByVolume(
                $merchantReference,
                $this->shippingMethod,
                $totalVolume,
                $this->quoteTransfer->getAddressDistance(),
            );
        }

        return $this->getFinalShippingCost($cost, $merchantReference);
    }

    /**
     * Get the final shipping cost
     *
     * @param object $cost
     * @param string $merchantReference
     *
     * @return int
     */
    private function getFinalShippingCost(
        $cost,
        string $merchantReference,
    ): int {
        $shippingCost = -1;

        if (!$cost) {
            $shippingCostData = $this->getFacade()->getMaxShippingMethodCost($merchantReference, $this->shippingMethod);
            $shippingCost = $shippingCostData?->getCost() ?? -1;
            $this->dealerFreightDetail = $shippingCostData;
        } else {
            $this->dealerFreightDetail = $cost;
            $shippingCost = $cost?->getCost() ?? -1;
        }

        return $shippingCost;
    }

    /**
     * Get total weight and volume of quote item
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    private function getDeadWeightAndCubicWeight(ItemTransfer $item): array
    {
        $productAbstractId = $item->getIdProductAbstract();
        $productAbstract = new ProductAbstractIdTransfer();
        $productAbstract->setIdProductAbstract($productAbstractId);
        [$weight, $volume] = $this->processQuoteWeightAndVolumn($productAbstract);
        $totalWeight = $weight * $item->getQuantity();
        $totalVolume = (float)(
            (
                $volume[ShipmentConfig::LENGTH] * $volume[ShipmentConfig::HEIGHT]
                * $volume[ShipmentConfig::WIDTH]) / ShipmentConfig::SHIPMENT_VOLUME_FACTOR
            ) * $item->getQuantity();

        return [$totalWeight, $totalVolume];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractIdTransfer $productAbstract
     *
     * @return array
     */
    public function processQuoteWeightAndVolumn(ProductAbstractIdTransfer $productAbstract): array
    {
        $weight = 0;
        $volumnString = 0;
        $volume = [
            ShipmentConfig::HEIGHT => (float)$volumnString,
            ShipmentConfig::WIDTH => (float)$volumnString,
            ShipmentConfig::LENGTH => (float)$volumnString,
        ];

        $productAttribute = $this->getFacade()->getQuoteItemWeightAndVolume($productAbstract);
        $attrData = json_decode($productAttribute);
        if ($attrData) {
            $weight = (float)($attrData->weight ?? 0);
            $volume = [
                ShipmentConfig::HEIGHT => (float)($attrData->dimension_h ?? 0),
                ShipmentConfig::WIDTH => (float)($attrData->dimension_w ?? 0),
                ShipmentConfig::LENGTH => (float)($attrData->dimension_l ?? 0),
            ];
        }

        return [$weight, $volume];
    }

    /**
     * @param string $methodName
     *
     * @return int
     */
    public function getMethodDeliveryTime(string $methodName): int
    {
        $this->getPrice($methodName);
        if ($this->dealerFreightDetail) {
            return $this->dealerFreightDetail->getIdDealerFreight();
        }

        return 0;
    }

    /**
     * @param string $methodName
     * @param string $deliveryMode
     *
     * @return bool
     */
    public function getShippingMethodDelivery(string $methodName, string $deliveryMode): bool
    {
        $this->getPrice($methodName);
        if ($this->quoteTransfer->getDeliveryMode() == $deliveryMode) {
            return (bool)$this->dealerFreightDetail;
        }

        return false;
    }
}
