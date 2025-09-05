<?php

declare(strict_types=1);

namespace Mailtrap\Api\General;

use Mailtrap\Api\AbstractApi;
use Mailtrap\ConfigInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Billing
 */
class Billing extends AbstractApi implements GeneralInterface
{
    public function __construct(ConfigInterface $config, private int $accountId)
    {
        parent::__construct($config);
    }

    /**
     * Get current billing cycle usage for Email Testing and Email Sending.
     *
     * @return ResponseInterface
     */
    public function getBillingUsage(): ResponseInterface
    {
        return $this->handleResponse(
            $this->httpGet(sprintf('%s/api/accounts/%s/billing/usage', $this->getHost(), $this->getAccountId()))
        );
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }
}
