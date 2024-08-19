<?php

namespace FondOfKudu\Zed\KlettiesPaymentConnector\Business\PaymentMethodFilter;

use ArrayObject;
use FondOfKudu\Shared\KlettiesPaymentConnector\KlettiesPaymentConnectorConstants;
use FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class KlettiesPaymentMethodFilter implements KlettiesPaymentMethodFilterInterface
{
    /**
     * @var \FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig
     */
    protected $config;

    /**
     * @param \FondOfKudu\Zed\KlettiesPaymentConnector\KlettiesPaymentConnectorConfig $config
     */
    public function __construct(KlettiesPaymentConnectorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @var string
     */
    public const UNTRANSLATED_ATTRIBUTES_KEY = '_';

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        if ($this->hasPaymentRestrictedItems($quoteTransfer) === false) {
            return $paymentMethodsTransfer;
        }

        return $this->removePrepaymentFromPaymentMethods($paymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasPaymentRestrictedItems(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $attributes = $itemTransfer->getAbstractAttributes();

            if (!isset($attributes[static::UNTRANSLATED_ATTRIBUTES_KEY])) {
                continue;
            }

            if (!isset($attributes[static::UNTRANSLATED_ATTRIBUTES_KEY][KlettiesPaymentConnectorConstants::ITEM_ATTRIBUTE_CASEABLE_SKU])) {
                continue;
            }

            if (empty($attributes[static::UNTRANSLATED_ATTRIBUTES_KEY][KlettiesPaymentConnectorConstants::ITEM_ATTRIBUTE_CASEABLE_SKU])) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function removePrepaymentFromPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): PaymentMethodsTransfer {
        $filteredPaymentMethods = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if (in_array($paymentMethodTransfer->getMethodName(), $this->config->getNotAllowedPaymentMethods(), true)) {
                continue;
            }

            $filteredPaymentMethods->append($paymentMethodTransfer);
        }

        return $paymentMethodsTransfer->setMethods($filteredPaymentMethods);
    }
}
