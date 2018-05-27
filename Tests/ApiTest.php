<?php

namespace Andser\BitfinexBundle\Tests;

use Andser\BitfinexBundle\Model\Stats;
use Andser\BitfinexBundle\Model\Ticker;
use Andser\BitfinexBundle\Service\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ApiTest
 */
class ApiTest extends TestCase
{
    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getTicker()
     */
    public function testGetTicker()
    {
        $json = '{  
           "mid":"7528.85",
           "bid":"7528.8",
           "ask":"7528.9",
           "last_price":"7525.9",
           "low":"7350.1",
           "high":"7624.1",
           "volume":"11453.50601286",
           "timestamp":"1527365209.0034723"
        }';
        $json400 = '{"message": "Unknown symbol"}';
        $mock = new MockHandler([
            new Response(200, [], $json),
            new Response(400, [], $json400),
        ]);
        $api = $this->createApi($mock);
        $ticker = $api->getTicker('btcusd');
        $this->assertInstanceOf(Ticker::class, $ticker);
        $this->assertEquals(7528.85, $ticker->getMid());
        $this->assertEquals(7528.8, $ticker->getBid());
        $this->assertEquals(7528.9, $ticker->getAsk());
        $this->assertEquals(7525.9, $ticker->getLastPrice());
        $this->assertEquals(7350.1, $ticker->getLow());
        $this->assertEquals(7624.1, $ticker->getHigh());
        $this->assertEquals(11453.50601286, $ticker->getVolume());
        $this->assertEquals((new \DateTime())->setTimestamp(1527365209.0034723), $ticker->getTimestamp());
        $this->expectException(ClientException::class);
        $api->getTicker('btcusd2');
    }

    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getStats()
     */
    public function testGetStats()
    {
        $json = '[{
          "period":1,
          "volume":"7967.96766158"
        },{
          "period":7,
          "volume":"55938.67260266"
        },{
          "period":30,
          "volume":"275148.09653645"
        }]';
        $json400 = '{"message": "Unknown symbol"}';
        $mock = new MockHandler([
            new Response(200, [], $json),
            new Response(400, [], $json400),
        ]);
        $api = $this->createApi($mock);
        /** @var Stats[] $stats */
        $stats = $api->getStats('btcusd');
        $this->assertInternalType('array', $stats);
        $this->assertCount(3, $stats);
        $this->assertInstanceOf(Stats::class, $stats[0]);
        $this->assertInstanceOf(Stats::class, $stats[1]);
        $this->assertInstanceOf(Stats::class, $stats[2]);
        $this->assertEquals(1, $stats[0]->getPeriod());
        $this->assertEquals(7967.96766158, $stats[0]->getVolume());
        $this->assertEquals(7, $stats[1]->getPeriod());
        $this->assertEquals(55938.67260266, $stats[1]->getVolume());
        $this->assertEquals(30, $stats[2]->getPeriod());
        $this->assertEquals(275148.09653645, $stats[2]->getVolume());
        $this->expectException(ClientException::class);
        $api->getStats('btcusd2');
    }

    /**
     * @param MockHandler $mock
     *
     * @return Api
     */
    protected function createApi(MockHandler $mock)
    {
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return new Api(new Serializer([new ObjectNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]), $client);
    }
}
