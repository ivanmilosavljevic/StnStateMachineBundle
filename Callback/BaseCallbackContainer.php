<?php

namespace Stn\StateMachineBundle\Callback;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Stn\StateMachineBundle\Exception\InvalidStateCallbackException;
use Stn\StateMachineBundle\Exception\UndefinedStateCallbackException;

/**
 * The base callback container
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
abstract class BaseCallbackContainer implements CallbackContainerInterface, ContainerAwareCallbackInterface
{
    /**
     * @var array<Closure>
     */
    protected $callbacks;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->initialize();
    }

    /**
     * Initialize callbacks and *must* be initialized
     *
     * @example
     * ```
     * $this->callbacks = array(
     *   'onPrePending' => function () {
     *     // Do something ...
     *   }
     * );
     * ```
     */
    abstract public function initialize();

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        // Is callback's valid
        if (false === $this->isValid($name)) {
            throw new \InvalidArgumentException(sprintf('The callback named "%s" is unvalid, must contain a prefix "on".', $name));
        }

        // Does callback exist
        if (false === $this->has($name)) {
            throw new UndefinedStateCallbackException(sprintf('Callback with name "%s" does not exist.', $name));
        }

        // Is callback a Closure
        if ( ! ($callback = $this->callbacks[$name]) instanceof \Closure) {
            throw new InvalidStateCallbackException(sprintf('Expected callback which named "%s" is not a Closure.', $name));
        }

        return $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        if (null === $this->callbacks) {
            return false;
        }

        foreach ($this->callbacks as $cbName => $callback) {
            if ($name === $cbName) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->callbacks;
    }

    /**
     * Whether callback's name(method) is vaild, *must* contain a prefix "on"
     *
     * @param $name The callback name
     * @return boolean
     */
    protected function isValid($name)
    {
        return preg_match("/^on.*$/", $name) > 0;
    }
}
