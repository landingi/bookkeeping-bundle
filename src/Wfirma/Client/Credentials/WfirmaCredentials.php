<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Credentials;

final class WfirmaCredentials
{
    private string $login;
    private string $password;
    private int $companyId;

    public function __construct(string $login, string $password, int $companyId)
    {
        $this->login = $login;
        $this->password = $password;
        $this->companyId = $companyId;
    }

    public function toString(): string
    {
        return sprintf('%s:%s', $this->login, $this->password);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }
}
