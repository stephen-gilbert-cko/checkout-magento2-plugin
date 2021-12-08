<?php

/**
 * Checkout.com
 * Authorized and regulated as an electronic money institution
 * by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * PHP version 7
 *
 * @category  Magento2
 * @package   Checkout.com
 * @author    Platforms Development Team <platforms@checkout.com>
 * @copyright 2010-2019 Checkout.com
 * @license   https://opensource.org/licenses/mit-license.html MIT License
 * @link      https://docs.checkout.com/
 */

namespace CheckoutCom\Magento2\Model\Service;

use Magento\Customer\Model\Session;

/**
 * Class MethodHandlerService.
 */
class MethodHandlerService
{
    /**
     * $instances field
     *
     * @var array $instances
     */
    public $instances;
    /**
     * $orderHandler field
     *
     * @var OrderHandlerService $orderHandler
     */
    public $orderHandler;
    /**
     * $customerSession field
     *
     * @var Session $customerSession
     */
    public $customerSession;

    /**
     * MethodHandlerService constructor
     *
     * @param                     $instances
     * @param OrderHandlerService $orderHandler
     * @param Session             $customerSession
     */
    public function __construct(
        $instances,
        \CheckoutCom\Magento2\Model\Service\OrderHandlerService $orderHandler,
        Session $customerSession
    ) {
        $this->instances       = $instances;
        $this->orderHandler    = $orderHandler;
        $this->customerSession = $customerSession;
    }

    /**
     * Description get function
     *
     * @param $methodId
     *
     * @return mixed
     */
    public function get($methodId)
    {
        return $this->instances[$methodId];
    }

    /**
     * Retrieves the customers last APM payment source
     *
     * @return null
     */
    public function getPreviousSource()
    {
        // Get the customer id (currently logged in user)
        $customerId = $this->customerSession->getCustomer()->getId();

        if ($customerId) {
            // Find the order from increment id
            $order = $this->orderHandler->getOrder([
                'customer_id' => $customerId,
            ]);

            if ($this->orderHandler->isOrder($order)) {
                if ($order->getPayment()->getAdditionalInformation('method_id') != null) {
                    return $order->getPayment()->getAdditionalInformation('method_id');
                } elseif ($order->getPayment()->getAdditionalInformation('public_hash') != null) {
                    return $order->getPayment()->getAdditionalInformation('public_hash');
                }
            }
        }

        return null;
    }

    /**
     * Retrieves the customers last used payment method
     *
     * @return null
     */
    public function getPreviousMethod()
    {
        // Get the customer id (currently logged in user)
        $customerId = $this->customerSession->getCustomer()->getId();

        if ($customerId) {
            // Find the order from increment id
            $order = $this->orderHandler->getOrder([
                'customer_id' => $customerId,
            ]);

            if ($this->orderHandler->isOrder($order)) {
                if ($order->getPayment()->getMethod() !== null) {
                    return $order->getPayment()->getMethod();
                }
            }
        }

        return null;
    }
}
