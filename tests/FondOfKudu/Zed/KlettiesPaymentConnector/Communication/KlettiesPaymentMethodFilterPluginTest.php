<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector\Communication;

use Codeception\Test\Unit;
use FondOfKudu\Zed\KlettiesPaymentConnector\Business\KlettiesPaymentConnectorFacade;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class KlettiesPaymentMethodFilterPluginTest extends Unit
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
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\KlettiesPaymentConnectorFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $facadeMock;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Communication\KlettiesPaymentMethodFilterPlugin
     */
    protected $plugin;

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

        $this->facadeMock = $this->getMockBuilder(KlettiesPaymentConnectorFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new KlettiesPaymentMethodFilterPlugin();
        $this->plugin->setFacade($this->facadeMock);
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethods(): void
    {
        $this->facadeMock->expects(static::atLeastOnce())
            ->method('filterPaymentMethods')
            ->with($this->paymentMethodsTransferMock, $this->quoteTransferMock)
            ->willReturn($this->paymentMethodsTransferMock);

        $response = $this->plugin->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $response);
    }
}
