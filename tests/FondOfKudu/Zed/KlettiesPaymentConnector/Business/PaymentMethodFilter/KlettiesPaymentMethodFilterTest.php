<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter;

use Codeception\Test\Unit;
use FondOfKudu\Shared\KlettiesPaymentConnector\KlettiesPaymentConnectorConstants;
use FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class KlettiesPaymentMethodFilterTest extends Unit
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
     * @var \Generated\Shared\Transfer\PaymentTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $paymentTransferMock;

    /**
     * @var \Generated\Shared\Transfer\PaymentMethodTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $paymentMethodTransferMock;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $itemTransferMock;

    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter\KlettiesPaymentMethodFilter
     */
    protected $klettiesPaymentMethodFilter;

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

        $this->paymentMethodsTransferMock = $this->getMockBuilder(PaymentMethodsTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentMethodTransferMock = $this->getMockBuilder(PaymentMethodTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentTransferMock = $this->getMockBuilder(PaymentTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->klettiesPaymentConnectorConfigMock = $this->getMockBuilder(KlettiesPaymentConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->klettiesPaymentMethodFilter = new KlettiesPaymentMethodFilter($this->klettiesPaymentConnectorConfigMock);
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsNoUntranslatedAttributes(): void
    {
        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn([$this->itemTransferMock]);

        $this->itemTransferMock->expects(static::atLeastOnce())
            ->method('getAbstractAttributes')
            ->willReturn([]);

        $paymentMethodsTransfer = $this->klettiesPaymentMethodFilter->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $paymentMethodsTransfer);
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsNoCaseableSkuAttribute(): void
    {
        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn([$this->itemTransferMock]);

        $this->itemTransferMock->expects(static::atLeastOnce())
            ->method('getAbstractAttributes')
            ->willReturn(['_' => []]);

        $paymentMethodsTransfer = $this->klettiesPaymentMethodFilter->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $paymentMethodsTransfer);
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsCaseableSkuAttributeIsEmpty(): void
    {
        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn([$this->itemTransferMock]);

        $this->itemTransferMock->expects(static::atLeastOnce())
            ->method('getAbstractAttributes')
            ->willReturn(['_' => [KlettiesPaymentMethodFilter::UNTRANSLATED_ATTRIBUTES_KEY => '']]);

        $paymentMethodsTransfer = $this->klettiesPaymentMethodFilter->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $paymentMethodsTransfer);
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsHasCaseableItemsNoProviderRemove(): void
    {
        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn([$this->itemTransferMock]);

        $this->itemTransferMock->expects(static::atLeastOnce())
            ->method('getAbstractAttributes')
            ->willReturn(['_' => [KlettiesPaymentConnectorConstants::ITEM_ATTRIBUTE_CASEABLE_SKU => 'some_SKU']]);

        $this->paymentMethodsTransferMock->expects(static::atLeastOnce())
            ->method('getMethods')
            ->willReturn([$this->paymentMethodTransferMock]);

        $this->paymentMethodTransferMock->expects(static::atLeastOnce())
            ->method('getMethodName')
            ->willReturn('SOME_PAYMENT_PROVIDER');

        $this->klettiesPaymentConnectorConfigMock->expects(static::atLeastOnce())
            ->method('getNotAllowedPaymentMethods')
            ->willReturn(['REMOVE_PAYMENT_PROVIDER']);

        $this->paymentMethodsTransferMock->expects(static::atLeastOnce())
            ->method('setMethods')
            ->willReturnSelf();

        $paymentMethodsTransfer = $this->klettiesPaymentMethodFilter->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $paymentMethodsTransfer);
        static::assertCount(1, $paymentMethodsTransfer->getMethods());
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsHasCaseableItemsAndRemovePaymentProvider(): void
    {
        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn([$this->itemTransferMock]);

        $this->itemTransferMock->expects(static::atLeastOnce())
            ->method('getAbstractAttributes')
            ->willReturn(['_' => [KlettiesPaymentConnectorConstants::ITEM_ATTRIBUTE_CASEABLE_SKU => 'some_SKU']]);

        $this->paymentMethodsTransferMock->expects(static::atLeastOnce())
            ->method('getMethods')
            ->willReturn([$this->paymentMethodTransferMock]);

        $this->paymentMethodTransferMock->expects(static::atLeastOnce())
            ->method('getMethodName')
            ->willReturn('REMOVE_PAYMENT_PROVIDER');

        $this->klettiesPaymentConnectorConfigMock->expects(static::atLeastOnce())
            ->method('getNotAllowedPaymentMethods')
            ->willReturn(['REMOVE_PAYMENT_PROVIDER']);

        $this->paymentMethodsTransferMock->expects(static::atLeastOnce())
            ->method('setMethods')
            ->willReturnSelf();

        $paymentMethodsTransfer = $this->klettiesPaymentMethodFilter->filterPaymentMethods($this->paymentMethodsTransferMock, $this->quoteTransferMock);

        static::assertEquals($this->paymentMethodsTransferMock, $paymentMethodsTransfer);
    }
}
