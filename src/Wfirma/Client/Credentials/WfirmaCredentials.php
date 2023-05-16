<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Credentials;

final class WfirmaCredentials
{
    private string $accessKey;
    private string $secretKey;
    private string $appKey;
    private int $companyId;

    public function __construct(string $accessKey, string $secretKey, string $appKey, int $companyId)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->appKey = $appKey;
        $this->companyId = $companyId;
    }

    public function asHeaders(): array
    {
        return [
            'accessKey' => $this->accessKey,
            'secretKey' => $this->secretKey,
            'appKey' => $this->appKey,
        ];
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }
}
