<?php

namespace Stn\StateMachineBundle\Resolver;

use Stn\StateMachineBundle\Annotation\State;
use Stn\StateMachineBundle\Callback\CallbackCollection;

/**
 * The interface for resolving state's callback configuration
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
interface CallbackResolverInterface
{
	/**
	 * Resolve state's callback configuration as a callback
	 *
	 * @param array $state State configuration
	 */
	public function resolve(State $state);
}
