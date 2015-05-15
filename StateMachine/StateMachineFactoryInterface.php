<?php

namespace Stn\StateMachineBundle\StateMachine;

/**
 * Interface of state machine factory
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
interface StateMachineFactoryInterface
{
	/**
	 * Get state machine instrance
	 *
	 * @param \Stn\StateMachineBundle\StateMachine\BaseStateful $entity
	 * @param string $graph The state machine graph
	 * @return \Finite\StateMachine\StateMachine
	 *
	 * @throws \Stn\StateMachineBundle\Exception\UndefinedStateMachineException
	 */
	public function get(BaseStateful $entity, $graph);
}
