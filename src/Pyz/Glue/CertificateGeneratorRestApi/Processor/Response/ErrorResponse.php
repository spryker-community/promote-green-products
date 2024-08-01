<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CertificateGeneratorRestApi\Processor\Response;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Pyz\Glue\CertificateGeneratorRestApi\CertificateGeneratorRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidApiResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CertificateGeneratorRestApiConfig::ERROR_CODE_INVALID_API_ENDPOINT)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CertificateGeneratorRestApiConfig::ERROR_INFO_INVALID_API_ENDPOINT);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidQueryParameterResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CertificateGeneratorRestApiConfig::ERROR_CODE_INVALID_QUERY_PARAMETER)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CertificateGeneratorRestApiConfig::ERROR_INFO_INVALID_QUERY_PARAMETER);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidLocaleCodeResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CertificateGeneratorRestApiConfig::ERROR_CODE_INVALID_LOCALE)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CertificateGeneratorRestApiConfig::ERROR_INFO_INVALID_LOCALE);

        return $restResponse->addError($restErrorTransfer);
    }


    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $message
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidResponse(RestResponseInterface $restResponse, $message): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CertificateGeneratorRestApiConfig::ERROR_CODE_EXCEPTION)
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail($message);

        return $restResponse->addError($restErrorTransfer);
    }
}
