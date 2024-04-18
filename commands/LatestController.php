<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\ExchangeRateComponent;

class LatestController extends Controller
{
    /**
     * ./yii latest
     * @return void
     */
    public function actionIndex(): void
    {
        /**
         * @var ExchangeRateComponent $exchangeRateComponent
         */
        $exchangeRateComponent = Yii::$app->exchangeRate;
        $latestDTO = $exchangeRateComponent->getLatest();
        echo json_encode($latestDTO);
    }
}
