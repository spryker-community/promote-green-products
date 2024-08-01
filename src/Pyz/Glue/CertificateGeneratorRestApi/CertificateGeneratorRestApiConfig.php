<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CertificateGeneratorRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CertificateGeneratorRestApiConfig extends AbstractBundleConfig
{
    // request api endpoint
    /**
     * @var string
     */
    public const RESOURCE_API_ENDPOINT = 'certificate-generator';

    // request api endpoint controller
    /**
     * @var string
     */
    public const RESOURCE_API_ENDPOINT_CONTROLLER = 'certificate-generator-resource';

    // error code for invalid api endpoint
    /**
     * @var string
     */
    public const ERROR_CODE_INVALID_API_ENDPOINT = '101';

    // error info for invalid api endpoint
    /**
     * @var string
     */
    public const ERROR_INFO_INVALID_API_ENDPOINT = 'Invalid api end point.';

    // error code for invalid body parameter
    /**
     * @var string
     */
    public const ERROR_CODE_INVALID_QUERY_PARAMETER = '102';

    // error info for invalid body parameter
    /**
     * @var string
     */
    public const ERROR_INFO_INVALID_QUERY_PARAMETER = 'Query parameter is not valid for api.';

    // error code for invalid locale
    /**
     * @var string
     */
    public const ERROR_CODE_INVALID_LOCALE = '104';

    // error info for invalid locale
    /**
     * @var string
     */
    public const ERROR_INFO_INVALID_LOCALE = 'Requested locale code is not valid for api.';

    // error code for exception
    /**
     * @var string
     */
    public const ERROR_CODE_EXCEPTION = '111';
}
