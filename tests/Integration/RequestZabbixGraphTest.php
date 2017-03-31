<?php

namespace CasperBoone\ZabbixGraph\Test\Unit;

use CasperBoone\ZabbixGraph\ZabbixGraph;
use CasperBoone\ZabbixGraph\Test\TestCase;

class RequestZabbixGraphTest extends TestCase
{
    /** @test */
    public function a_graph_can_be_requested_from_the_zabbix_demo_installation()
    {
        $zabbixGraph = new ZabbixGraph('http://zabbix.org/zabbix/', 'is_not_required', 'and_also_not_checked');

        $graph = $zabbixGraph
            ->width(1000)
            ->height(500)
            ->find(77);
        $metaData = $this->imageMetaData($graph);

        $this->assertEquals(1116, $metaData['width']); // Graph provided by Zabbix is slightly bigger
        $this->assertEquals(690, $metaData['height']); // Graph provided by Zabbix is slightly bigger
        $this->assertEquals(3, $metaData['type']); // Verify that is a PNG
    }

    /** @test */
    public function a_graph_can_be_requested_from_a_non_public_zabbix_installation()
    {
        $this->loadenv("Integration test with non public Zabbix installation not executed because .env file is not present");

        $zabbixGraph = new ZabbixGraph(
            getenv('NON_PUBLIC_ZABBIX_HOST'),
            getenv('NON_PUBLIC_ZABBIX_USERNAME'),
            getenv('NON_PUBLIC_ZABBIX_PASSWORD')
        );

        $graph = $zabbixGraph
            ->width(1000)
            ->height(500)
            ->find(getenv('NON_PUBLIC_ZABBIX_GRAPHID'));
        $metaData = $this->imageMetaData($graph);

        $this->assertEquals(1116, $metaData['width']); // Graph provided by Zabbix is slightly bigger
        $this->assertEquals(732, $metaData['height']); // Graph provided by Zabbix is slightly bigger
        $this->assertEquals(3, $metaData['type']); // Verify that is a PNG
    }

    private function imageMetaData($binaryImage)
    {
        $tempFile = tempnam("/tmp", "image");

        $fileHandle = fopen($tempFile, 'w');
        fwrite($fileHandle, $binaryImage);

        list($width, $height, $type) = getimagesize($tempFile);

        fclose($fileHandle);

        return compact('width', 'height', 'type');
    }
}
