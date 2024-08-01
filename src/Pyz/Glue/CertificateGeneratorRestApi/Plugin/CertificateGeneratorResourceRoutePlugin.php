<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CertificateGeneratorRestApi\Plugin;

use Generated\Shared\Transfer\CertificateGeneratorTransfer;
use Generated\Shared\Transfer\RestVersionTransfer;
use Pyz\Glue\CertificateGeneratorRestApi\CertificateGeneratorRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceVersionableInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouterParameterExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class CertificateGeneratorResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, ResourceVersionableInterface, RouterParameterExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection->addGet('get', false);

        return $resourceRouteCollection;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return CertificateGeneratorRestApiConfig::RESOURCE_API_ENDPOINT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return CertificateGeneratorRestApiConfig::RESOURCE_API_ENDPOINT_CONTROLLER;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return CertificateGeneratorRestApiConfig::class;
    }

    /**
     * Function to mention api version
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    public function getVersion(): RestVersionTransfer
    {
        return (new RestVersionTransfer())
            ->setMajor(1)
            ->setMinor(0);
    }

    /**
     * Function expands resource configuration with additional parameters
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param array $resourceConfiguration
     *
     * @return array<mixed>
     */
    public function expandResourceConfiguration(ResourceRoutePluginInterface $resourceRoutePlugin, array $resourceConfiguration): array
    {
        return $resourceConfiguration;
    }

    /**
     * Function expands route parameters with additional parameters
     *
     * @param array $resourceConfiguration
     * @param array $routeParams
     *
     * @return array<mixed>
     */
    public function expandRouteParameters(array $resourceConfiguration, array $routeParams): array
    {
        $routeParams[RequestConstantsInterface::ATTRIBUTE_RESOURCE_VERSION] = $resourceConfiguration[RequestConstantsInterface::ATTRIBUTE_RESOURCE_VERSION] ?? '';

        return $routeParams;
    }
}
