StnStateMachineBundle
=====================

This bundle provides annotation tool to use state machine(requires [yohang/Finite](https://github.com/yohang/Finite)) with Symfony and makes transitions more flexible.

Usage
-----

### Define your state

``` php
<?php

namespace Stn\StateMachineBundle\Example;

use Doctrine\ORM\Mapping as ORM;
use Stn\StateMachineBundle\Annotation\State;
use Stn\StateMachineBundle\StateMachine\BaseStateful;

/**
 * Orders
 *
 * @State(
 *   class="Stn\StateMachineBundle\Example\Orders",
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
 *   class="Stn\StateMachineBundle\Example\Orders",
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
 *   class="Stn\StateMachineBundle\Example\Orders",
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
     * @ORM\Column(name="orderNo", type="string", length=32)
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

    // Setters and getters ...
}

```

The state configuration base on *yohang/Finite*, see [examples](https://github.com/yohang/Finite/tree/master/examples).

**Notice:**
1. If you don't want to save the state data to database, just remove the Doctrine ORM mapping annotations;
2. Closure is unsupported in the `callbacks` configuration which under `State` annotaition, you should provide a service id(e.g. `@stn_state_machine.callback.order`).

### Use callbacks

There is a easy way to use callbacks, just defines a callback container which extends `Stn\StateMachineBundle\Callback\BaseCallbackContainer` and registers it as a service.

``` php
<?php

namespace Stn\StateMachineBundle\Example;

use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

use Stn\StateMachineBundle\Callback\BaseCallbackContainer;

/**
 * Callback container of Order
 */
class OrderCallbacks extends BaseCallbackContainer
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        $container = $this->container;

        $this->callbacks = array(
            // Basic use
            'onPrePending' => function () {
                echo "This callback will be trigger before state was translated from 'PENDING'.";
            },
            // Use service container
            'onPostPending' => function (StatefulInterface $order, TransitionEvent $event) use ($container) {
                // Fetch a service and do something
                // $service = $container->get(SOME_SERVICE);

                echo "This callback will be trigger after state was translated from 'PENDING'.";
            }
        );
    }
}
```

``` yaml
stn_state_machine.callback.order:
    class: Stn\StateMachineBundle\Example\OrderCallbacks
    arguments: [ @service_container ]
```

### Fetch state machine

``` php
// In controller
$staetMachineFactory = $this->get('stn_state_machine.state_machine.factory');

$order = new Orders();
$order->setOrderNo(date('Ymd-His'));

// dump($order);

$orderStateMachine = $staetMachineFactory->get($order);
// If you define multiple graph,
// you need to specify `graph` in the state annotation, and then use second parameter to fetch state machine
$paymentStateMachine = $staetMachineFactory->get($order, 'payment');
$shipmentStateMachine = $staetMachineFactory->get($order, 'shipment');

if ($orderStateMachine->can('handle')) {
    $orderStateMachine->apply('handle');
}

if ($paymentStateMachine->can('pay')) {
    $paymentStateMachine->apply('pay');
}

if ($shipmentStateMachine->can('ship')) {
    $shipmentStateMachine->apply('ship');
}

// dump($order);
 ```
