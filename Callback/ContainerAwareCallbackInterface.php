<?php

namespace Stn\StateMachineBundle\Callback;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A callback container interface to using Symfony service container
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
interface ContainerAwareCallbackInterface
{
    /**
     * Set service container
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Get service container
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer();
}
