<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector\Business;

use Codeception\Test\Unit;
use FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilter;
use FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig;

class KlettiesPaymentConnectorBusinessFactoryTest extends Unit
{
    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\KlettiesPaymentConnectorBusinessFactory
     */
    protected $businessFactory;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilter|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $klettiesPaymentMethodFilterMock;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $klettiesPaymentConnectorConfigMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->klettiesPaymentMethodFilterMock = $this->getMockBuilder(KlettiesPaymentMethodFilter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->klettiesPaymentConnectorConfigMock = $this->getMockBuilder(KlettiesPaymentConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->businessFactory = new KlettiesPaymentConnectorBusinessFactory();
        $this->businessFactory->setConfig($this->klettiesPaymentConnectorConfigMock);
    }

    /**
     * @return void
     */
    public function testCreateKlettiesPaymentMethodFilter(): void
    {
        $this->businessFactory->createKlettiesPaymentMethodFilter($this->klettiesPaymentConnectorConfigMock);
    }
}
