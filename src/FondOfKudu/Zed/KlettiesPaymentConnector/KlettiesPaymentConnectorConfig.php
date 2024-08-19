<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector;

use FondOfKudu\Shared\KlettiesPaymentConnector\KlettiesPaymentConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class KlettiesPaymentConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getNotAllowedPaymentMethods(): array
    {
        return $this->get(KlettiesPaymentConnectorConstants::NOT_ALLOWED_PAYMENT_METHODS, []);
    }
}
