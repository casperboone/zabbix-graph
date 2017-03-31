<?php

namespace CasperBoone\ZabbixGraph\Test\Unit;

use Mockery;
use CasperBoone\ZabbixGraph\ZabbixGraph;

class FakeZabbixGraph extends ZabbixGraph
{
    /**
     * Create HTTP client for requesting the graph. The HTTP client should preserve cookies.
     *
     * @param  string $url Full url of Zabbix location
     * @return HttpClient
     */
    protected function createHttpClient($url)
    {
        $this->url = $url;

        return Mockery::spy(\GuzzleHttp\ClientInterface::class);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function httpClient()
    {
        return $this->httpClient;
    }
}
