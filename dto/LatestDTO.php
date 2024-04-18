<?php
namespace app\dto;

class LatestDTO
{
    public string $disclaimer;
    public string $license;
    public int $timestamp;
    public string $base;
    /** @var LatestRateDTO[] */
    public array $rates;
}