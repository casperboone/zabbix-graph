<?php

namespace CasperBoone\ZabbixGraph\Test\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

class ZabbixGraphTest extends TestCase
{
    /**
     * @var FakeZabbixGraph
     */
    private $zabbixGraph;

    protected function setUp()
    {
        parent::setUp();

        $this->zabbixGraph = new FakeZabbixGraph('http://example.com', 'john', 'welcome');

        $this->setUpMocks();
    }

    /** @test */
    public function it_can_login_to_zabbix()
    {
        $this->zabbixGraph->find(1);

        $this->assertRequestHasField('name', 'john', 'post');
        $this->assertRequestHasField('password', 'welcome', 'post');

        // Verify if path is correct
        $this->assertEquals('http://example.com', $this->zabbixGraph->url);
        $this->zabbixGraph->httpClient()->shouldHaveReceived('post')->with('index.php', Mockery::any());
    }

    /** @test */
    public function it_can_request_a_graph_with_correct_defaults()
    {
        $image = $this->zabbixGraph->find(123);

        $this->assertEquals('image', $image);
        $this->assertRequestHasField('graphid', 123);
        $this->assertRequestHasField('width', 400);
        $this->assertRequestHasField('height', 200);

        // Verify if path is correct
        $this->zabbixGraph->httpClient()->shouldHaveReceived('get')->with('chart2.php', Mockery::any());
    }

    /** @test */
    public function a_custom_width_can_be_set_and_is_included_in_the_request()
    {
        $this->zabbixGraph
            ->width(123)
            ->find(1);

        $this->assertRequestHasField('width', 123);
    }

    /** @test */
    public function a_custom_height_can_be_set_and_is_included_in_the_request()
    {
        $this->zabbixGraph
            ->height(123)
            ->find(1);

        $this->assertRequestHasField('height', 123);
    }

    /** @test */
    public function a_custom_start_time_can_be_set_and_is_included_in_the_request()
    {
        $this->zabbixGraph
            ->startTime((new \DateTime())->setTimestamp(1445758080))
            ->find(1);

        $this->assertRequestHasField('stime', 1445758080);
    }

    /** @test */
    public function a_custom_end_time_can_be_set_and_is_included_in_the_request()
    {
        $this->zabbixGraph
            ->startTime((new \DateTime())->setTimestamp(10))
            ->endTime((new \DateTime())->setTimestamp(100))
            ->find(1);

        $this->assertRequestHasField('period', 90);
    }

    private function assertRequestHasField($key, $value, $method = 'get')
    {
        $requestDataKey = [
            'get' => 'query',
            'post' => 'form_params',
        ][$method];

        $this->zabbixGraph->httpClient()->shouldHaveReceived($method)
            ->with(Mockery::any(), Mockery::on(function ($options) use ($requestDataKey, $key, $value) {
                $this->assertEquals($value, $options[$requestDataKey][$key]);

                return true;
            }));
    }

    private function setUpMocks()
    {
        $this->zabbixGraph->httpClient()->shouldReceive('get')
            ->andReturn($response = Mockery::mock(ResponseInterface::class));
        $response->shouldReceive('getBody')
            ->andReturn($message = Mockery::mock(MessageInterface::class));
        $message->shouldReceive('getContents')
            ->andReturn('image');
    }
}
