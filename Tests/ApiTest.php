<?php

namespace Andser\BitfinexBundle\Tests;

use Andser\BitfinexBundle\Model\LendBook;
use Andser\BitfinexBundle\Model\OrderBook;
use Andser\BitfinexBundle\Model\Stats;
use Andser\BitfinexBundle\Model\Ticker;
use Andser\BitfinexBundle\Model\Trade;
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
     * @covers \Andser\BitfinexBundle\Service\Api::getLendBook()
     */
    public function testGetLendbook()
    {
        $json = '{
           "bids":[
              {
                 "rate":"18.2537",
                 "amount":"247368.42401616",
                 "period":30,
                 "timestamp":"1527449883.0",
                 "frr":"No"
              },
              {
                 "rate":"18.2504",
                 "amount":"57385.36233362",
                 "period":30,
                 "timestamp":"1527446282.0",
                 "frr":"Yes"
              }
           ],
           "asks":[
              {
                 "rate":"18.2135",
                 "amount":"1265.37940124",
                 "period":2,
                 "timestamp":"1527453291.0",
                 "frr":"No"
              },
              {
                 "rate":"19.4275",
                 "amount":"59.97029939",
                 "period":2,
                 "timestamp":"1527453435.0",
                 "frr":"Yes"
              },
              {
                 "rate":"19.464",
                 "amount":"420.0",
                 "period":2,
                 "timestamp":"1527453378.0",
                 "frr":"No"
              }
           ]
        }';
        $json400 = '{"message": "Unknown currency"}';
        $mock = new MockHandler([
            new Response(200, [], $json),
            new Response(400, [], $json400),
        ]);
        $api = $this->createApi($mock);
        /** @var LendBook $lendbook */
        $lendbook = $api->getLendBook('btcusd');
        $this->assertInstanceOf(LendBook::class, $lendbook);
        $this->assertCount(2, $lendbook->getBids());
        $this->assertCount(3, $lendbook->getAsks());
        $this->assertEquals(18.2537, $lendbook->getBids()[0]->getRate());
        $this->assertEquals(247368.42401616, $lendbook->getBids()[0]->getAmount());
        $this->assertEquals(30, $lendbook->getBids()[0]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527449883.0), $lendbook->getBids()[0]->getTimestamp());
        $this->assertFalse($lendbook->getBids()[0]->hasFlashReturnRate());

        $this->assertEquals(18.2504, $lendbook->getBids()[1]->getRate());
        $this->assertEquals(57385.36233362, $lendbook->getBids()[1]->getAmount());
        $this->assertEquals(30, $lendbook->getBids()[1]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527446282.0), $lendbook->getBids()[1]->getTimestamp());
        $this->assertTrue($lendbook->getBids()[1]->hasFlashReturnRate());

        $this->assertEquals(18.2135, $lendbook->getAsks()[0]->getRate());
        $this->assertEquals(1265.37940124, $lendbook->getAsks()[0]->getAmount());
        $this->assertEquals(2, $lendbook->getAsks()[0]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527453291.0), $lendbook->getAsks()[0]->getTimestamp());
        $this->assertFalse($lendbook->getAsks()[0]->hasFlashReturnRate());

        $this->assertEquals(19.4275, $lendbook->getAsks()[1]->getRate());
        $this->assertEquals(59.97029939, $lendbook->getAsks()[1]->getAmount());
        $this->assertEquals(2, $lendbook->getAsks()[1]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527453435.0), $lendbook->getAsks()[1]->getTimestamp());
        $this->assertTrue($lendbook->getAsks()[1]->hasFlashReturnRate());

        $this->assertEquals(19.464, $lendbook->getAsks()[2]->getRate());
        $this->assertEquals(420.0, $lendbook->getAsks()[2]->getAmount());
        $this->assertEquals(2, $lendbook->getAsks()[2]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527453378.0), $lendbook->getAsks()[2]->getTimestamp());
        $this->assertFalse($lendbook->getAsks()[2]->hasFlashReturnRate());
        $this->expectException(ClientException::class);
        $api->getLendBook('btcusd2');
    }

    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getOrderBook()
     */
    public function testGetOrderBook()
    {
        $json = '{
           "bids":[
              {
                 "price":"7207.2",
                 "amount":"9.41706735",
                 "timestamp":"1527533065.0"
              },
              {
                 "price":"7206.9",
                 "amount":"0.024",
                 "timestamp":"1527533065.0"
              }
           ],
           "asks":[
              {
                 "price":"7207.3",
                 "amount":"3.90621616",
                 "timestamp":"1527533065.0"
              }
           ]
        }';
        $json400 = '{"message": "Unknown symbol"}';
        $mock = new MockHandler([
            new Response(200, [], $json),
            new Response(400, [], $json400),
        ]);
        $api = $this->createApi($mock);
        $orderbook = $api->getOrderBook('btcusd');
        $this->assertInstanceOf(OrderBook::class, $orderbook);
        $this->assertCount(2, $orderbook->getBids());
        $this->assertCount(1, $orderbook->getAsks());
        $this->assertEquals(9.41706735, $orderbook->getBids()[0]->getAmount());
        $this->assertEquals(7207.2, $orderbook->getBids()[0]->getPrice());
        $this->assertEquals((new \DateTime())->setTimestamp(1527533065.0), $orderbook->getBids()[0]->getTimestamp());
        $this->assertEquals(0.024, $orderbook->getBids()[1]->getAmount());
        $this->assertEquals(7206.9, $orderbook->getBids()[1]->getPrice());
        $this->assertEquals((new \DateTime())->setTimestamp(1527533065.0), $orderbook->getBids()[1]->getTimestamp());
        $this->assertEquals(3.90621616, $orderbook->getAsks()[0]->getAmount());
        $this->assertEquals(7207.3, $orderbook->getAsks()[0]->getPrice());
        $this->assertEquals((new \DateTime())->setTimestamp(1527533065.0), $orderbook->getAsks()[0]->getTimestamp());
        $this->expectException(ClientException::class);
        $api->getOrderBook('btcusd2');
    }

    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getTrades()
     */
    public function testGetTrades()
    {
        $json = '[
           {
              "timestamp":1527534721,
              "tid":251147276,
              "price":"7215.1",
              "amount":"0.06052527",
              "exchange":"bitfinex",
              "type":"buy"
           },
           {
              "timestamp":1527534721,
              "tid":251147275,
              "price":"7215.1",
              "amount":"0.03947473",
              "exchange":"bitfinex",
              "type":"sell"
           }
        ]';
        $json400 = '{"message": "Unknown symbol"}';
        $mock = new MockHandler([
            new Response(200, [], $json),
            new Response(400, [], $json400),
        ]);
        $api = $this->createApi($mock);
        $trades = $api->getTrades('btcusd');
        $this->assertInternalType('array', $trades);
        $this->assertCount(2, $trades);
        $this->assertInstanceOf(Trade::class, $trades[0]);
        $this->assertInstanceOf(Trade::class, $trades[1]);
        $this->assertEquals((new \DateTime())->setTimestamp(1527534721), $trades[0]->getTimestamp());
        $this->assertEquals(251147276, $trades[0]->getTid());
        $this->assertEquals(7215.1, $trades[0]->getPrice());
        $this->assertEquals(0.06052527, $trades[0]->getAmount());
        $this->assertEquals('bitfinex', $trades[0]->getExchange());
        $this->assertTrue($trades[0]->isBuy());
        $this->assertFalse($trades[0]->isSell());

        $this->assertEquals((new \DateTime())->setTimestamp(1527534721), $trades[1]->getTimestamp());
        $this->assertEquals(251147275, $trades[1]->getTid());
        $this->assertEquals(7215.1, $trades[1]->getPrice());
        $this->assertEquals(0.03947473, $trades[1]->getAmount());
        $this->assertEquals('bitfinex', $trades[1]->getExchange());
        $this->assertFalse($trades[1]->isBuy());
        $this->assertTrue($trades[1]->isSell());
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
