<?php

namespace Fraudshield;

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

        if($report->usesPostRequest()) {

            return $this->post($apiRequest, $report->parameters);
        }

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
     * @param string $uri
     * @return mixed
     */
    public function get($uri)
    {
        $url = $this->getBaseUrl($uri);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;

    }

    /**
     * @param string $uri
     * @return mixed
     */
    public function post($uri, $data)
    {


        $url = $this->getBaseUrl($uri);
        $httpQuery = http_build_query($data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $httpQuery);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
