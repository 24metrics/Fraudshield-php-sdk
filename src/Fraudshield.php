<?php
namespace Fraudshield;

class Fraudshield
{

    private $userId;
    private $apiToken;

    //@var string The base URL for the Fraudshield API.
    private $apiBase = "https://fraudshield.24metrics.com/api/v1/";


    public function __construct($userId, $apiToken)
    {
        $this->userId = $userId;
        $this->apiToken = $apiToken;
    }


    public function getReport($report)
    {

        $apiRequest = $report->getPartialApiRequest();

        return $this->get($apiRequest);
    }

    private function getBaseUrl($uri)
    {
       return  $this->apiBase.$uri.'&'.http_build_query(['user_id' => $this->userId, 'api_token' =>$this->apiToken]);
    }
    public function get($uri)
    {
        $url = $this->getBaseUrl($uri);

        var_dump($url); die();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;

    }
}