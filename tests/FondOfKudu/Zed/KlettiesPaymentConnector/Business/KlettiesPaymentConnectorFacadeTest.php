<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector\Business;

use Codeception\Test\Unit;
use FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilter;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class KlettiesPaymentConnectorFacadeTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\PaymentMethodsTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $paymentMethodsTransferMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\KlettiesPaymentConnectorBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $businessFactoryMock;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilter|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $klettiesPaymentMethodFilterMock;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\KlettiesPaymentConnectorFacade
     */
    protected $facade;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->paymentMethodsTransferMock = $this->getMockBuilder(PaymentMethodsTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->businessFactoryMock = $this->getMockBuilder(KlettiesPaymentConnectorBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->klettiesPaymentMethodFilterMock = $this->getMockBuilder(KlettiesPaymentMethodFilter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->facade = new KlettiesPaymentConnectorFacade();
        $this->facade->setFactory($this->businessFactoryMock);
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethods(): void
    {
        $this->businessFactoryMock->expects(static::atLeastOnce())
            ->method('createKlettiesPaymentMethodFilter')
            ->willReturn($this->klettiesPaymentMethodFilterMock);

        $this->klettiesPaymentMethodFilterMock->expects(static::atLeastOnce())
            ->method('filterPaymentMethods')
            ->with($this->paymentMethodsTransferMock, $this->quoteTransferMock)
            ->willReturn($this->paymentMethodsTransferMock);

        $paymentMethodsTransfer = $this->facade->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $paymentMethodsTransfer);
    }
}
