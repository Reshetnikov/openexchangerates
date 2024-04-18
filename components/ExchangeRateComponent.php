<?php
namespace app\components;

use app\dto\LatestRateDTO;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use app\dto\LatestDTO;
use yii\httpclient\Exception;

/**
 * @property Client $client
 */
class ExchangeRateComponent extends Component
{
    /**
     * @see config/console.php
     * @var string
     */
    public string $apiKey;
    /**
     * @see config/console.php
     * @var string
     */
    public string $apiUrl;

    protected Client $client;

    public function __construct($config = [])
    {
        $this->client = new Client();
        parent::__construct($config);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function getLatest(): LatestDTO
    {
        $response = $this->requestGet('/latest.json');
        $data = $response->getData();
        $latestDTO = new LatestDTO();
        $latestDTO->disclaimer = $data['disclaimer'];
        $latestDTO->license = $data['license'];
        $latestDTO->timestamp = $data['timestamp'];
        $latestDTO->base = $data['base'];

        $latestDTO->rates = [];
        foreach ($data['rates'] as $code => $rate) {
            $currencyRateDTO = new LatestRateDTO();
            $currencyRateDTO->code = $code;
            $currencyRateDTO->rate = $rate;
            $latestDTO->rates[] = $currencyRateDTO;
        }
        return $latestDTO;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getCurrencies(): void
    {
        $response = $this->requestGet('/currencies.json');
        // ...
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function requestGet(string $method): \yii\httpclient\Response
    {
        $response = $this->client->createRequest()
            ->setMethod('get')
            ->setUrl($this->apiUrl . $method)
            ->setData(['app_id' => $this->apiKey])
            ->send();
        if ($response->isOk) {
            return $response;
        } else {
            throw new \Exception('Failed to retrieve currencies.');
        }
    }
}