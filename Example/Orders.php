<?php

namespace Stn\StateMachineBundle\Example;

use Doctrine\ORM\Mapping as ORM;
use Stn\StateMachineBundle\Annotation\State;
use Stn\StateMachineBundle\StateMachine\BaseStateful;

/**
 * Orders
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 * @State(
 *   class="Stn\StateMachineBundle\Entity\Orders",
 *   graph="order",
 *   propertyPath="state",
 *   states={
 *     "PENDING"={
 *       "type"="initial"
 *     },
 *     "HANDLING"={
 *       "type"="normal"
 *     },
 *     "FINISHED"={
 *       "type"="final"
 *     },
 *     "CANCLED"={
 *       "type"="final"
 *     }
 *   },
 *   transitions={
 *     "handle"={
 *       "from"={ "PENDING" },
 *       "to"="HANDLING"
 *     },
 *     "finish"={
 *       "from"={ "PENDING", "HANDLING" },
 *       "to"="FINISHED"
 *     },
 *     "cancle"={
 *       "from"={ "PENDING", "HANDLING" },
 *       "to"="CANCLED"
 *     }
 *   },
 *   callbacks={
 *     "before"={
 *       { "from": "PENDING", "do": { "@stn_state_machine.callback.order", "onPrePending" } }
 *     },
 *     "after"={
 *       { "from": "PENDING", "do": { "@stn_state_machine.callback.order", "onPostPending" } }
 *     }
 *   }
 * )
 * @State(
 *   class="Stn\StateMachineBundle\Entity\Orders",
 *   graph="payment",
 *   propertyPath="paymentState",
 *   states={
 *     "PAYING"={
 *       "type"="initial"
 *     },
 *     "PAIED"={
 *       "type"="final"
 *     }
 *   },
 *   transitions={
 *     "pay"={
 *       "from"={ "PAYING" },
 *       "to"="PAIED"
 *     }
 *   }
 * )
 * @State(
 *   class="Stn\StateMachineBundle\Entity\Orders",
 *   graph="shipment",
 *   propertyPath="shippingState",
 *   states={
 *     "SHIPPING"={
 *       "type"="initial"
 *     },
 *     "SHIPPED"={
 *       "type"="final"
 *     }
 *   },
 *   transitions={
 *     "ship"={
 *       "from"={ "SHIPPING" },
 *       "to"="SHIPPED"
 *     }
 *   }
 * )
 */
class Orders extends BaseStateful
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="orderNo", type="string", length=255)
     */
    private $orderNo;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=16, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentState", type="string", length=16, nullable=true)
     */
    private $paymentState;

    /**
     * @var string
     *
     * @ORM\Column(name="shippingState", type="string", length=16, nullable=true)
     */
    private $shippingState;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderNo
     *
     * @param string $orderNo
     *
     * @return Orders
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    /**
     * Get orderNo
     *
     * @return string
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Orders
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set paymentState
     *
     * @param string $paymentState
     *
     * @return Orders
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    /**
     * Get paymentState
     *
     * @return string
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * Set shippingState
     *
     * @param string $shippingState
     *
     * @return Orders
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;

        return $this;
    }

    /**
     * Get shippingState
     *
     * @return string
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }
}
