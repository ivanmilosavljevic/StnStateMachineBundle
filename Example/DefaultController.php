<?php

namespace Stn\StateMachineBundle\Example;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Stn\StateMachineBundle\Example\Orders;

class DefaultController extends Controller
{
    /**
     * @Route("/test/state-machine")
     */
    public function testSmAction()
    {
        $staetMachineFactory = $this->get('stn_state_machine.state_machine.factory');

        $order = new Orders();
        $order->setOrderNo(date('Ymd-His'));

        dump($order);

        $orderStateMachine = $staetMachineFactory->get($order);
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

        dump($order);

        return new Response('State Machine');
    }
}
