<?php

namespace Fraudshield;

use Fraudshield\Exception\UnexpectedResponseException;
use Fraudshield\Reports\Report;

class Fraudshield
{

    private $userId;
    private $apiToken;

    /* @var string $apiBase The base URL for the Fraudshield API. */
    private $apiBase = "https://fraudshield.24metrics.com/api/v1/";


    /**
     * Fraudshield constructor.
     * @param int $userId
     * @param string $apiToken
     */
    public function __construct($userId, $apiToken)
    {
        $this->userId = $userId;
        $this->apiToken = $apiToken;
    }


    /**
     * @param Report $report
     * @return mixed
     */
    public function getReport(Report $report)
    {

        $apiRequest = $report->getPartialApiRequest();

        return $this->get($apiRequest);
    }

    /**
     * @param string $uri
     * @return string
     */
    private function getBaseUrl($uri)
    {
        return $this->apiBase . $uri . '&' . http_build_query([
                'user_id' => $this->userId,
                'api_token' => $this->apiToken
            ]);
    }


    /**
     * @param $uri
     * @return mixed
     * @throws UnexpectedResponseException
     */
    public function get($uri)
    {
        $url = $this->getBaseUrl($uri);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $headers = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $string) use (&$headers) {
            $parts = explode(":", $string, 2);

            if (count($parts) == 2) {
                $name = trim($parts[0]);
                $value = trim($parts[1]);

                $headers[$name] = $value;
            }

            return strlen($string);
        });

        $body = curl_exec($ch);
        $info = curl_getinfo($ch);


        if ($info['http_code'] != 200) {
            $message = sprintf(
                "Request failed with response code %d for url %s\n",
                $info['http_code'],
                $url
            );

            throw new UnexpectedResponseException($message, $headers, $info,  $body);
        }

        curl_close($ch);

        return $body;
    }
}
