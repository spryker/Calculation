<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

interface CalculatorInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer);
}