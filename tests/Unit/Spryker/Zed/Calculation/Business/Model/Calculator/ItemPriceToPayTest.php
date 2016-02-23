<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use Spryker\Zed\Sales\Business\Model\CalculableContainer;

/**
 * Class ItemPriceToPayTest
 *
 * @group ItemPriceToPayTest
 * @group Calculation
 */
class ItemPriceToPayTest extends \PHPUnit_Framework_TestCase
{

    const ITEM_GROSS_PRICE = 10000;
    const ITEM_SALESRULE_DISCOUNT_AMOUNT = 100;
    const ITEM_COUPON_DISCOUNT_AMOUNT = 50;

    /**
     * @return void
     */
    public function testPriceToPayShouldReturnItemGrossPriceForAnOrderWithOneItem()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $calculator = new ItemPriceToPayCalculator();
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(self::ITEM_GROSS_PRICE, $item->getPriceToPay());
        }
    }

    /**
     * @return void
     */
    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountForAnOrderWithOneItemWithCouponDiscountAmmount()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $calculator = new ItemPriceToPayCalculator();
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT, $item->getPriceToPay());
        }
    }

    /**
     * @return void
     */
    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithOneItemAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $calculator = new ItemPriceToPayCalculator();
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    /**
     * @return void
     */
    public function testPriceToPayShouldReturnItemGrossPriceMinusCouponDiscountAmountMinusDiscountAmountForAnOrderWithTwoItemsAndBothDiscounts()
    {
        $order = $this->getOrderWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_COUPON_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $discount = $this->getPriceDiscount();
        $discount->setAmount(self::ITEM_SALESRULE_DISCOUNT_AMOUNT);
        $item->addDiscount($discount);

        $order->getCalculableObject()->addItem($item);
        $order->getCalculableObject()->addItem(clone $item);
        $calculator = new ItemPriceToPayCalculator();
        $calculator->recalculate($order);

        foreach ($order->getCalculableObject()->getItems() as $item) {
            $this->assertEquals(
                self::ITEM_GROSS_PRICE - self::ITEM_COUPON_DISCOUNT_AMOUNT - self::ITEM_SALESRULE_DISCOUNT_AMOUNT,
                $item->getPriceToPay()
            );
        }
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function getPriceDiscount()
    {
        $discountTransfer = new DiscountTransfer();

        return $discountTransfer;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new ItemTransfer();

        return $item;
    }

}
