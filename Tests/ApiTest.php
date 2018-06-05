<?php

namespace Andser\BitfinexBundle\Tests;

use Andser\BitfinexBundle\Model\FundingBook;
use Andser\BitfinexBundle\Model\Lend;
use Andser\BitfinexBundle\Model\OrderBook;
use Andser\BitfinexBundle\Model\Stats;
use Andser\BitfinexBundle\Model\Symbol;
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
     * @covers \Andser\BitfinexBundle\Service\Api::getFundingBook()
     */
    public function testGetFundingBook()
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
        /** @var FundingBook $lendbook */
        $fundingbook = $api->getFundingBook('btcusd');
        $this->assertInstanceOf(FundingBook::class, $fundingbook);
        $this->assertCount(2, $fundingbook->getBids());
        $this->assertCount(3, $fundingbook->getAsks());
        $this->assertEquals(18.2537, $fundingbook->getBids()[0]->getRate());
        $this->assertEquals(247368.42401616, $fundingbook->getBids()[0]->getAmount());
        $this->assertEquals(30, $fundingbook->getBids()[0]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527449883.0), $fundingbook->getBids()[0]->getTimestamp());
        $this->assertFalse($fundingbook->getBids()[0]->hasFlashReturnRate());

        $this->assertEquals(18.2504, $fundingbook->getBids()[1]->getRate());
        $this->assertEquals(57385.36233362, $fundingbook->getBids()[1]->getAmount());
        $this->assertEquals(30, $fundingbook->getBids()[1]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527446282.0), $fundingbook->getBids()[1]->getTimestamp());
        $this->assertTrue($fundingbook->getBids()[1]->hasFlashReturnRate());

        $this->assertEquals(18.2135, $fundingbook->getAsks()[0]->getRate());
        $this->assertEquals(1265.37940124, $fundingbook->getAsks()[0]->getAmount());
        $this->assertEquals(2, $fundingbook->getAsks()[0]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527453291.0), $fundingbook->getAsks()[0]->getTimestamp());
        $this->assertFalse($fundingbook->getAsks()[0]->hasFlashReturnRate());

        $this->assertEquals(19.4275, $fundingbook->getAsks()[1]->getRate());
        $this->assertEquals(59.97029939, $fundingbook->getAsks()[1]->getAmount());
        $this->assertEquals(2, $fundingbook->getAsks()[1]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527453435.0), $fundingbook->getAsks()[1]->getTimestamp());
        $this->assertTrue($fundingbook->getAsks()[1]->hasFlashReturnRate());

        $this->assertEquals(19.464, $fundingbook->getAsks()[2]->getRate());
        $this->assertEquals(420.0, $fundingbook->getAsks()[2]->getAmount());
        $this->assertEquals(2, $fundingbook->getAsks()[2]->getPeriod());
        $this->assertEquals((new \DateTime())->setTimestamp(1527453378.0), $fundingbook->getAsks()[2]->getTimestamp());
        $this->assertFalse($fundingbook->getAsks()[2]->hasFlashReturnRate());
        $this->expectException(ClientException::class);
        $api->getFundingBook('btcusd2');
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
        $this->expectException(ClientException::class);
        $api->getTrades('btcusd2');
    }

    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getLends()
     */
    public function testGetLends()
    {
        $json = '
        [
           {
              "rate":"14.7602",
              "amount_lent":"177191.98677715",
              "amount_used":"172729.73344517",
              "timestamp":1528225501
           },
           {
              "rate":"14.6878",
              "amount_lent":"178566.7106906",
              "amount_used":"174602.60739049",
              "timestamp":1528221901
           }
        ]';
        $json400 = '{"message": "Unknown currency"}';
        $mock = new MockHandler([
            new Response(200, [], $json),
            new Response(400, [], $json400),
        ]);
        $api = $this->createApi($mock);
        $lends = $api->getLends('eth');
        $this->assertInternalType('array', $lends);
        $this->assertCount(2, $lends);
        $this->assertInstanceOf(Lend::class, $lends[0]);
        $this->assertInstanceOf(Lend::class, $lends[1]);

        $this->assertEquals((new \DateTime())->setTimestamp(1528225501), $lends[0]->getTimestamp());
        $this->assertEquals(177191.98677715, $lends[0]->getAmountLent());
        $this->assertEquals(172729.73344517, $lends[0]->getAmountUsed());
        $this->assertEquals(14.7602, $lends[0]->getRate());
        $this->assertEquals((new \DateTime())->setTimestamp(1528221901), $lends[1]->getTimestamp());
        $this->assertEquals(178566.7106906, $lends[1]->getAmountLent());
        $this->assertEquals(174602.60739049, $lends[1]->getAmountUsed());
        $this->assertEquals(14.6878, $lends[1]->getRate());

        $this->expectException(ClientException::class);
        $api->getLends('btcusd2');
    }

    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getSymbols()
     */
    public function testGetSymbols()
    {
        $json = '[
           "btcusd",
           "ltcusd",
           "ltcbtc",
           "ethusd",
           "ethbtc",
           "etcbtc",
           "etcusd",
           "rrtusd",
           "rrtbtc",
           "zecusd",
           "zecbtc",
           "xmrusd",
           "xmrbtc",
           "dshusd",
           "dshbtc",
           "btceur",
           "btcjpy",
           "xrpusd",
           "xrpbtc",
           "iotusd",
           "iotbtc",
           "ioteth",
           "eosusd",
           "eosbtc",
           "eoseth",
           "sanusd",
           "sanbtc",
           "saneth",
           "omgusd",
           "omgbtc",
           "omgeth",
           "bchusd",
           "bchbtc",
           "bcheth",
           "neousd",
           "neobtc",
           "neoeth",
           "etpusd",
           "etpbtc",
           "etpeth",
           "qtmusd",
           "qtmbtc",
           "qtmeth",
           "avtusd",
           "avtbtc",
           "avteth",
           "edousd",
           "edobtc",
           "edoeth",
           "btgusd",
           "btgbtc",
           "datusd",
           "datbtc",
           "dateth",
           "qshusd",
           "qshbtc",
           "qsheth",
           "yywusd",
           "yywbtc",
           "yyweth",
           "gntusd",
           "gntbtc",
           "gnteth",
           "sntusd",
           "sntbtc",
           "snteth",
           "ioteur",
           "batusd",
           "batbtc",
           "bateth",
           "mnausd",
           "mnabtc",
           "mnaeth",
           "funusd",
           "funbtc",
           "funeth",
           "zrxusd",
           "zrxbtc",
           "zrxeth",
           "tnbusd",
           "tnbbtc",
           "tnbeth",
           "spkusd",
           "spkbtc",
           "spketh",
           "trxusd",
           "trxbtc",
           "trxeth",
           "rcnusd",
           "rcnbtc",
           "rcneth",
           "rlcusd",
           "rlcbtc",
           "rlceth",
           "aidusd",
           "aidbtc",
           "aideth",
           "sngusd",
           "sngbtc",
           "sngeth",
           "repusd",
           "repbtc",
           "repeth",
           "elfusd",
           "elfbtc",
           "elfeth",
           "btcgbp",
           "etheur",
           "ethjpy",
           "ethgbp",
           "neoeur",
           "neojpy",
           "neogbp",
           "eoseur",
           "eosjpy",
           "eosgbp",
           "iotjpy",
           "iotgbp",
           "iosusd",
           "iosbtc",
           "ioseth",
           "aiousd",
           "aiobtc",
           "aioeth",
           "requsd",
           "reqbtc",
           "reqeth",
           "rdnusd",
           "rdnbtc",
           "rdneth",
           "lrcusd",
           "lrcbtc",
           "lrceth",
           "waxusd",
           "waxbtc",
           "waxeth",
           "daiusd",
           "daibtc",
           "daieth",
           "cfiusd",
           "cfibtc",
           "cfieth",
           "agiusd",
           "agibtc",
           "agieth",
           "bftusd",
           "bftbtc",
           "bfteth",
           "mtnusd",
           "mtnbtc",
           "mtneth",
           "odeusd",
           "odebtc",
           "odeeth",
           "antusd",
           "antbtc",
           "anteth",
           "dthusd",
           "dthbtc",
           "dtheth",
           "mitusd",
           "mitbtc",
           "miteth",
           "stjusd",
           "stjbtc",
           "stjeth",
           "xlmusd",
           "xlmeur",
           "xlmjpy",
           "xlmgbp",
           "xlmbtc",
           "xlmeth",
           "xvgusd",
           "xvgeur",
           "xvgjpy",
           "xvggbp",
           "xvgbtc",
           "xvgeth",
           "bciusd",
           "bcibtc",
           "mkrusd",
           "mkrbtc",
           "mkreth",
           "venusd",
           "venbtc",
           "veneth",
           "kncusd",
           "kncbtc",
           "knceth",
           "poausd",
           "poabtc",
           "poaeth",
           "lymusd",
           "lymbtc",
           "lymeth",
           "utkusd",
           "utkbtc",
           "utketh",
           "veeusd",
           "veebtc",
           "veeeth",
           "dadusd",
           "dadbtc",
           "dadeth"
        ]';
        $mock = new MockHandler([
            new Response(200, [], $json),
        ]);
        $api = $this->createApi($mock);
        $symbols = $api->getSymbols();
        $this->assertInternalType('array', $symbols);
        $this->assertCount(204, $symbols);
    }

    /**
     * @covers \Andser\BitfinexBundle\Service\Api::getSymbolsDetails()
     */
    public function testGetSymbolsDetails()
    {
        $json = '[
           {
              "pair":"btcusd",
              "price_precision":5,
              "initial_margin":"30.0",
              "minimum_margin":"15.0",
              "maximum_order_size":"2000.0",
              "minimum_order_size":"0.002",
              "expiration":"NA",
              "margin":true
           },
           {
              "pair":"ltcusd",
              "price_precision":7,
              "initial_margin":"130.0",
              "minimum_margin":"115.0",
              "maximum_order_size":"5000.0",
              "minimum_order_size":"0.08",
              "expiration":"NA",
              "margin":false
           },
           {
              "pair":"ltcbtc",
              "price_precision":5,
              "initial_margin":"30.0",
              "minimum_margin":"15.0",
              "maximum_order_size":"5000.0",
              "minimum_order_size":"0.08",
              "expiration":"NA",
              "margin":true
           }
        ]';
        $mock = new MockHandler([
            new Response(200, [], $json),
        ]);
        $api = $this->createApi($mock);
        $details = $api->getSymbolsDetails();
        $this->assertInternalType('array', $details);
        $this->assertCount(3, $details);
        $this->assertInstanceOf(Symbol::class, $details[0]);
        $this->assertInstanceOf(Symbol::class, $details[1]);
        $this->assertInstanceOf(Symbol::class, $details[2]);
        $this->assertEquals('btcusd', $details[0]->getPair());
        $this->assertEquals(5, $details[0]->getPricePrecision());
        $this->assertEquals(30.0, $details[0]->getInitialMargin());
        $this->assertEquals(15.0, $details[0]->getMinimumMargin());
        $this->assertEquals(2000.0, $details[0]->getMaximumOrderSize());
        $this->assertEquals(0.002, $details[0]->getMinimumOrderSize());
        $this->assertNull($details[0]->getExpiration());
        $this->assertTrue($details[0]->isMargin());

        $this->assertEquals('ltcusd', $details[1]->getPair());
        $this->assertEquals(7, $details[1]->getPricePrecision());
        $this->assertEquals(130.0, $details[1]->getInitialMargin());
        $this->assertEquals(115.0, $details[1]->getMinimumMargin());
        $this->assertEquals(5000.0, $details[1]->getMaximumOrderSize());
        $this->assertEquals(0.08, $details[1]->getMinimumOrderSize());
        $this->assertNull($details[1]->getExpiration());
        $this->assertFalse($details[1]->isMargin());

        $this->assertEquals('ltcbtc', $details[2]->getPair());
        $this->assertEquals(5, $details[2]->getPricePrecision());
        $this->assertEquals(30.0, $details[2]->getInitialMargin());
        $this->assertEquals(15.0, $details[2]->getMinimumMargin());
        $this->assertEquals(5000.0, $details[2]->getMaximumOrderSize());
        $this->assertEquals(0.08, $details[2]->getMinimumOrderSize());
        $this->assertNull($details[2]->getExpiration());
        $this->assertTrue($details[2]->isMargin());
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
