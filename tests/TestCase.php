<?php

namespace CasperBoone\ZabbixGraph\Test;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public function loadenv($absentEnvMessage = null)
    {
        try {
            (new Dotenv(__DIR__.'/../'))->load();
        } catch (InvalidPathException $exception) {
            $this->markTestSkipped($absentEnvMessage ?? 'Env file not present');

            return;
        }
    }
}
