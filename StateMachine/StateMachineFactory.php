<?php

namespace Stn\StateMachineBundle\StateMachine;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Finite\Loader\ArrayLoader;
use Finite\StateMachine\StateMachine;

use Stn\StateMachineBundle\Annotation\State;
use Stn\StateMachineBundle\Resolver\CallbackResolver;
use Stn\StateMachineBundle\Exception\InitailizedStateMachineException;
use Stn\StateMachineBundle\Exception\UndefinedStateConfigurationException;

/**
 * State machine factory
 *
 * @author Santino Wu<santinowu.wsq@gmail.com>
 */
class StateMachineFactory implements StateMachineFactoryInterface
{
	/**
	 * @var \Doctrine\Common\Annotations\Reader
	 */
	private $reader;

	/**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	private $container;

	/**
	 * @param \Doctrine\Common\Annotations\Reader $reader
	 */
	public function setReader(Reader $reader)
	{
		$this->reader = $reader;
	}

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

	/**
	 * Load state configuration
	 *
	 * @param \Stn\StateMachineBundle\StateMachine\BaseStateful $entity
	 *
	 * @return array<\Stn\StateMachineBundle\Annotation\State>
	 */
	protected function load(BaseStateful $entity)
	{
		$annotations = $this->reader->getClassAnnotations(
			ClassUtils::newReflectionObject($entity)
		);

		$states = array();
		$state = null;

		$resolver = new CallbackResolver();
		$resolver->setContainer($this->container);

		foreach ($annotations as $annotation) {
			if ($annotation instanceof State) {
				$state = $resolver->resolve($annotation);

				array_push($states, $state);
			}
		}

		return $states;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get(BaseStateful $entity, $graph)
	{
		$loader = null;
		$stateMachine = null;
		$states = $this->load($entity);

		if (empty($states)) {
			throw new UndefinedStateConfigurationException(sprintf(
				'State configuration with graph "%s" is undefined',
				$graph
			));
		}

		foreach ($states as $state) {
			if ($graph === $state->getGraph()) {
				$loader = new ArrayLoader($state->toArray());
				$stateMachine = new StateMachine($entity);

				$loader->load($stateMachine);
				$stateMachine->initialize();

				break;
			}
		}

		if (null === $stateMachine) {
			throw new InitailizedStateMachineException(sprintf(
				'Fail to initialize state machine with graph "%s"',
				$graph
			));
		}

		return $stateMachine;
	}
}
