<?php

namespace Stn\StateMachineBundle\Callback;

/**
 * The container for maintaining callbacks
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
interface CallbackContainerInterface
{
    /**
     * Get callback with its name
     *
     * @return Closure
     *
     * @throws \InvalidArgumentException
     *         \Stn\StateMachineBundle\Exception\InvalidStateCallbackException
     *         \Stn\StateMachineBundle\Exception\UndefinedStateCallbackException
     */
    public function get($name);

    /**
     * Whether callback with name is exists
     *
     * @return boolean
     */
    public function has($name);

    /**
     * List callbacks
     *
     * @return array<Closere>|null
     */
    public function all();
}
