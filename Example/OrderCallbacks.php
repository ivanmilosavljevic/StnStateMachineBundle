<?php

namespace Stn\StateMachineBundle\Example;

use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

use Stn\StateMachineBundle\Callback\BaseCallbackContainer;

/**
 * Callback container of Order
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
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
