<?php
namespace unit\components;

use app\components\ExchangeRateComponent;
use app\dto\LatestDTO;
use app\dto\LatestRateDTO;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;
use yii\httpclient\Exception;

class ExchangeRateComponentTest extends TestCase
{
    /**
     * ./vendor/bin/codecept run tests/unit/components/ExchangeRateComponentTest.php
     * @return void
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function testGetLatest()
    {
        $apiKey = 'API_KEY';
        $apiUrl = 'https://openexchangerates.org/api';
        $responseJson = '{"disclaimer":"Usage subject to terms: https://openexchangerates.org/terms","license":"https://openexchangerates.org/license","timestamp":1713463200,"base":"USD","rates":{"AED":3.6726,"AFN":71.999996,"ALL":95.18,"AMD":394.07,"ANG":1.801845}}';
        $expectedDTO = new LatestDTO();
        $expectedDTO->disclaimer = 'Usage subject to terms: https://openexchangerates.org/terms';
        $expectedDTO->license = 'https://openexchangerates.org/license';
        $expectedDTO->timestamp = 1713463200;
        $expectedDTO->base = 'USD';
        $expectedRates = [
            ['code' => 'AED', 'rate' => 3.6726],
            ['code' => 'AFN', 'rate' => 71.999996],
            ['code' => 'ALL', 'rate' => 95.18],
            ['code' => 'AMD', 'rate' => 394.07],
            ['code' => 'ANG', 'rate' => 1.801845],
        ];
        foreach ($expectedRates as $rateData) {
            $rateDTO = new LatestRateDTO();
            $rateDTO->code = $rateData['code'];
            $rateDTO->rate = $rateData['rate'];
            $expectedDTO->rates[] = $rateDTO;
        }

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('__get')->with('isOk')->willReturn('true');
        $mockResponse->method('getData')->willReturn(json_decode($responseJson, true));

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method('setMethod')->willReturn($mockRequest);
        $mockRequest->method('setUrl')->willReturn($mockRequest);
        $mockRequest->method('setData')->willReturn($mockRequest);
        $mockRequest->method('send')->willReturn($mockResponse);

        $mockClient = $this->createMock(Client::class);
        $mockClient->method('createRequest')->willReturn($mockRequest);

        $component = new ExchangeRateComponent(['apiKey' => $apiKey, 'apiUrl' => $apiUrl]);
        // Используем рефлексию для получения доступа к protected свойству $client
        $reflection = new \ReflectionClass($component);
        $property = $reflection->getProperty('client');
        $property->setValue($component, $mockClient);

        $latestDTO = $component->getLatest();

        $this->assertEquals($expectedDTO, $latestDTO);
    }
}
