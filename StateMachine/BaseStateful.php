<?php

namespace Stn\StateMachineBundle\StateMachine;

use Finite\StatefulInterface;

/**
 * The base class for class using state machine to extend form
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
abstract class BaseStateful implements StatefulInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getFiniteState()
	{
		if (false === method_exists($this, 'getState')) {
			throw new \RuntimeException('You should define a property named "status" and privide a method named "getState"');
		}

		return $this->getState();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setFiniteState($state)
	{
		if (false === method_exists($this, 'setState')) {
			throw new \RuntimeException('You should define a property named "status" and privide a method named "setState"');
		}

		$this->setState($state);
	}
}
