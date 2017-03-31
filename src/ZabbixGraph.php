<?php

namespace CasperBoone\ZabbixGraph;

use DateTime;
use GuzzleHttp\Client as HttpClient;

class ZabbixGraph
{
    protected $httpClient;
    protected $oldZabbix;
    protected $username;
    protected $password;

    protected $width = 400;
    protected $height = 200;
    protected $startTime;
    protected $endTime;

    /**
     * Construct and initalize ZabbixGraph.
     *
     * @param  string  $url        Full url of Zabbix location
     * @param  string  $username   Zabbix username
     * @param  string  $password   Zabbix password
     * @param  bool    $oldZabbix  Set to true if using Zabbix 1.8 or older, by default set to false
     */
    public function __construct($url, $username, $password, $oldZabbix = false)
    {
        $this->httpClient = $this->createHttpClient($url);
        $this->oldZabbix = $oldZabbix;
        $this->username = $username;
        $this->password = $password;

        $this->startTime = (new DateTime())->modify('-1 hour');
        $this->endTime = new DateTime();
    }

    /**
     * Create HTTP client for requesting the graph. The HTTP client should preserve cookies.
     *
     * @param  string  $url  Full url of Zabbix location
     * @return HttpClient
     */
    protected function createHttpClient($url) {
        return new HttpClient([
            'base_uri' => $url,
            'cookies' => true,
        ]);
    }

    /**
     * Request graph from Zabbix and return a raw image. If an error
     * occurred Zabbix will output this as an image.
     *
     * @param  int  $graphId  ID of the graph to be requested
     * @return string
     */
    public function find($graphId)
    {
        $this->login();

        return $this->httpClient->get('chart2.php', [
            'query' => [
                'graphid' => $graphId,
                'width' => $this->width,
                'height' => $this->height,
                'stime' => $this->startTime->getTimestamp(),
                'period' => $this->endTime->getTimestamp() - $this->startTime->getTimestamp(),
            ]
        ])->getBody()->getContents();
    }

    /**
     * Login to Zabbix to acquire the needed session for requesting the graph.
     */
    protected function login()
    {
        $this->httpClient->post('index.php', [
            'form_params' => [
                'name' => $this->username,
                'password' => $this->password,
                'enter' => 'Sign in',
            ]
        ]);
    }

    /**
     * Specify width of the graph in pixels, by default 400.
     *
     * @param  int  $width  Width in pixels
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Specify height of the graph in pixels, by default 400.
     *
     * @param  int  $height  Height in pixels
     * @return $this
     */
    public function height($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Specify start date and time of the data displayed in the graph.
     *
     * @param  DateTime  $startTime  Start date and time
     * @return $this
     */
    public function startTime(DateTime $startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Specify end date and time of the data displayed in the graph.
     *
     * @param  DateTime  $endTime  End date and time
     * @return $this
     */
    public function endTime(DateTime $endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }
}