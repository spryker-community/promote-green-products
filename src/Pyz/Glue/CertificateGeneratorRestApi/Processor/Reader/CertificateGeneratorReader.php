<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CertificateGeneratorRestApi\Processor\Reader;

use Exception;
use Generated\Shared\Transfer\CertificateGeneratorTransfer;
use Pyz\Glue\CertificateGeneratorRestApi\CertificateGeneratorRestApiConfig;
use Pyz\Glue\CertificateGeneratorRestApi\Processor\Response\ErrorResponse;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Generated\Shared\Transfer\EventEntityTransfer;
use Dompdf\Dompdf;
use Dompdf\Options;

class CertificateGeneratorReader implements CertificateGeneratorReaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Pyz\Glue\CustomPaymentRestApi\Processor\Response\ErrorResponse
     */
    protected $errorResponse;

    /**
     * __construct
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Pyz\Glue\CustomPaymentRestApi\Processor\Response\ErrorResponse $errorResponse
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ErrorResponse $errorResponse,
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->errorResponse = $errorResponse;
    }

    /**
     * Function to process data
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function process(RestRequestInterface $restRequest): RestResponseInterface
    {
        // logic to get data
        return $this->getCertificateGenerator($restRequest);
    }

    /**
     * Function to getCustomPaymentData data
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCertificateGenerator(RestRequestInterface $restRequest): RestResponseInterface
    {
        try {
            // check api endpoint is correct or not
            if ($restRequest->getResource()->getType() !== CertificateGeneratorRestApiConfig::RESOURCE_API_ENDPOINT) {
                return $this->errorResponse->createInvalidApiResponse($this->restResourceBuilder->createRestResponse());
            }

            // check name and score index are valid or not
            $contentData = $restRequest->getHttpRequest()->query->all();
            $name = ($contentData['name']) ?? '';
            $score = ($contentData['score']) ?? '';

             // check api endpoint parent value is not empty
            if (empty($name) || empty($score)) {
                return $this->errorResponse->createInvalidQueryParameterResponse($this->restResourceBuilder->createRestResponse());
            }       

            return $this->getStorageData($restRequest);
        } catch (Exception $e) {
            return $this->errorResponse->createInvalidResponse($this->restResourceBuilder->createRestResponse(), $e->getMessage());
        }
    }

    /**
     * Prepare Data for rest API
     *
     * @param object $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getStorageData($restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $contentData = $restRequest->getHttpRequest()->query->all();
        $name = strtolower($contentData['name']) ?? '';
        $score = strtolower($contentData['score']) ?? '';

        // Initialize DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Enables PHP within HTML (optional)
        $dompdf = new Dompdf($options);

        $date = (date('Y-m-d'));
        $html = $this->html($name, $score, $date);
        // Load HTML into DOMPDF
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $dompdf->stream('certificate.pdf', array('Attachment' => 1));

        // Stream the PDF to the browser for download
        $response['response'] = true;
       
         $restResource = $this->getTransferData($response);
        return $restResponse->addResource($restResource);
    }

     /**
      * @param array $storageData
      *
      * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
      */
    protected function getTransferData($storageData): RestResourceInterface
    {
        $messageStorageTransfer = new CertificateGeneratorTransfer();
        $messageStorageTransfer->fromArray($storageData, true);

        return $this->buildGetResourceResource($messageStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CertificateGeneratorTransfer $messageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildGetResourceResource(CertificateGeneratorTransfer $messageStorageTransfer): RestResourceInterface
    {
        return $this->restResourceBuilder->createRestResource(CertificateGeneratorRestApiConfig::RESOURCE_API_ENDPOINT, null, $messageStorageTransfer);
    }

    protected function html($name, $score, $date){
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Certificate of Carbon Emission Reduction</title>
                <style>
            body {
                font-family: "Roboto", sans-serif;
                background-color: #f0f9f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .certificate {
                background-color: #ffffff;
                width: 100%;
                border-radius: 15px;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
                padding: 40px;
                text-align: center;
                border: 4px dashed #4caf50;
                position: relative;
                overflow: hidden;
                box-sizing: border-box;

            }

            .certificate img {
                max-width: 150px;
                height: auto;
                margin-bottom: 20px;
            }

            .certificate h1 {
                font-family: "Pacifico", cursive;
                font-size: 36px;
                margin-bottom: 25px;
                color: #4caf50;
            }

            .certificate p {
                font-size: 18px;
                line-height: 1.6;
                color: #333;
                margin: 10px 0;
            }

            .certificate .details {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .certificate .summary {
                text-align: left;
                margin-top: 30px;
                padding: 15px;
                background-color: #e0f7fa;
                border-radius: 8px;
                border-left: 5px solid #4caf50;
            }

            .certificate .summary p {
                margin: 8px 0;
            
            }

            .certificate .signature {
                margin-top: 40px;
                font-size: 14px;
                text-align: left;
            }

            .certificate .signature div {
                margin-bottom: 5px;
                font-weight: bold;
            }

            .certificate .decorative-line {
                width: 120px;
                height: 6px;
                background-color: #4caf50;
                margin: 20px auto;
                border-radius: 3px;
            }

            .certificate strong {
                font-weight: bold;
            }

            .certificate .greenery {
                position: absolute;
                bottom: 10px;
                right: 10px;
                max-width: 100px;
                opacity: 0.8;
            }
    </style>
        </head>
        <body>
            <div class="certificate">
                <img src="https://pbs.twimg.com/profile_images/1675738898792579073/HZyBrP93_400x400.png" alt="Company Logo">
                <h1>Yayy!! You are a green club member now</h1>
                <div class="decorative-line"></div>
                <div class="details">
                    <p><strong>Date:</strong>'. $date.'</p>
                </div>
                <p>Dear <strong>'.ucfirst($name). '</strong>,</p>
                <p>Hurrah! You’ve taken a fantastic step towards a greener future with your recent purchase from Nagarro Electronics! By choosing our eco-friendly products, you’re not just buying a product—you’re supporting a movement, one that’s vibrant, joyful, and green!.</p>
                <p><strong> And, please dont’t forget to share this badge with your friends and family to promote consicious shopping.</strong> </p>
                <div class="summary">
                    <p>Carbon Emission Reduction Summary:</p>
                    <p>Total Carbon Emissions Saved:' . $score.' kg of CO₂</p>
                </div>
               <p>Our Promise:</p>
                <p>At Nagarro Electronics, we believe in a brighter, more sustainable tomorrow. Your choice to support our eco-friendly initiatives helps us spread joy and make a real impact. Together, we’re making the world a happier, greener place!</p>
                <p>Thanks for being a part of this amazing journey!</p>
                <div class="signature">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/0a/Nagarro_Horizontal_Light_400x100px_300dpi.png" alt="Nagarro Logo">
                    <div>Nagarro Electronics</div>
                </div>
                <img class="greenery" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTn8hn39HcJD51Qig3-txW339Lw8h7zb2YewQ&s" alt="Greenery">
            </div>
        </body>
        </html>';

    }
}
