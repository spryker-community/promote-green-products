<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CertificateGeneratorRestApi;

use Pyz\Glue\CertificateGeneratorRestApi\Processor\Reader\CertificateGeneratorReader;
use Pyz\Glue\CertificateGeneratorRestApi\Processor\Response\ErrorResponse;
use Spryker\Glue\Kernel\AbstractFactory;

class CertificateGeneratorRestApiFactory extends AbstractFactory
{
    /**
     * createCertificateGeneratorReader
     *
     * @return \Pyz\Glue\CertificateGeneratorRestApi\Processor\Reader\CustomPaymentReaderInterface
     */
    public function createCertificateGeneratorReader()
    {
        return new CertificateGeneratorReader(
            $this->getResourceBuilder(),
            $this->getErrorResponse()
        );
    }

   
     /**
      * @return \Pyz\Glue\CertificateGeneratorRestApi\Processor\Response\ErrorResponse
      */
    public function getErrorResponse()
    {
        return new ErrorResponse();
    }
}
