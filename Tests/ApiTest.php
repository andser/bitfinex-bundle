<?php

namespace Andser\BitfinexBundle\Tests;

use Andser\BitfinexBundle\Model\Ticker;
use Andser\BitfinexBundle\Service\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiTest
 */
class ApiTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
//    protected $serializer;

//    public function setUp()
//    {
//        $kernel = self::bootKernel();
//        $this->serializer = $kernel->getContainer()->get('serializer');
//    }

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
        $mock = new MockHandler([
            new Response(200, [], $json),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $api = new Api(new Serializer([new ObjectNormalizer()], [new JsonEncoder()]), $client);
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
    }
}
