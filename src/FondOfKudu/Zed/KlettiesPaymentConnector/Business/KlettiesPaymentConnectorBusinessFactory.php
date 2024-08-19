<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector\Business;

use FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilter;
use FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig getConfig()
 */
class KlettiesPaymentConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilterInterface
     */
    public function createKlettiesPaymentMethodFilter(): KlettiesPaymentMethodFilterInterface
    {
        return new KlettiesPaymentMethodFilter($this->getConfig());
    }
}
