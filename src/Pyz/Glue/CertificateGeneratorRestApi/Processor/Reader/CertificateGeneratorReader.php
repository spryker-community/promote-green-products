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

        // Load HTML content
        // $html = '
        //     <html>
        //     <head>
        //         <style>
        //             body { font-family: Arial, sans-serif; }
        //             h1 { color: #333; }
        //             p { margin: 0 0 10px 0; }
        //         </style>
        //     </head>
        //     <body>
        //         <h1>Hello DOMPDF!</h1>
        //         <p>This is an example of generating PDF from HTML using DOMPDF.</p>
        //         <p>HTML content can include <b>bold</b>, <i>italic</i>, and <u>underlined</u> text, as well as <a href="#">links</a>.</p>
        //     </body>
        //     </html>
        // ';
        $html = $this->html();
        // Load HTML into DOMPDF
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // $dompdf->stream('certificate.pdf', array('Attachment' => 1));

        // // // Save the generated PDF to a file
        $output = $dompdf->output();
        $filePath = '/data/public/Glue/certi.pdf';
        file_put_contents($filePath, $output);

        // // Stream the PDF to the browser for download

        $response['url'] = 'http://glue.at.spryker.local/exam.pdf';

         $customPaymentTransfer = new EventEntityTransfer();
         $customPayment['response'] = true;
         $restResource = $this->getTransferData($customPaymentTransfer);

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

    protected function html(){
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Carbon Emission Reduction</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .certificate {
            background-color: #ffffff;
            width: 750px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            border: 2px solid #2c3e50;
            position: relative;
            overflow: hidden;
        }

        .certificate img {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .certificate h1 {
            font-size: 30px;
            margin-bottom: 25px;
            color: #2c3e50;
            font-weight: bold;
        }

        .certificate p {
            font-size: 16px;
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
            background-color: #e8f5e9;
            border-radius: 8px;
            border-left: 5px solid #2c3e50;
        }

        .certificate .summary p {
            margin: 8px 0;
            font-weight: bold;
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
            background-color: #2c3e50;
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
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <img src="https://pbs.twimg.com/profile_images/1675738898792579073/HZyBrP93_400x400.png" alt="Company Logo">
        <h1>Certificate of Carbon Emission Reduction</h1>
        <div class="decorative-line"></div>
        <div class="details">
            <p><strong>Presented to:</strong> Anshika</p>
            <p><strong>Date:</strong> 01-08-2024</p>
        </div>
        <p><strong>Certificate Number:</strong> 1123</p>
        <p>Dear <strong>Anshika</strong>,</p>
        <p>Thank you for your recent purchase from our shop Nagarro Electronics. We are committed to promoting sustainability and reducing our carbon footprint. By choosing our products, you are contributing to a more sustainable future.</p>
        <div class="summary">
            <p>Carbon Emission Reduction Summary:</p>
            <p>Product Purchased: [Product Name/Description]</p>
            <p>Carbon Emission Reduction: [X]% reduction compared to conventional products</p>
            <p>Total Carbon Emissions Saved: [Y] kg of CO₂</p>
        </div>
        <p>Our Commitment:</p>
        <p>At our shop, we prioritize sustainability in every aspect of our business. We continually strive to minimize our environmental impact through eco-friendly practices and innovative solutions.</p>
        <p>Thank you for supporting our mission to create a healthier planet.</p>
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
