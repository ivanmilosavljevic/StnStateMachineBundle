<?php

namespace Stn\StateMachineBundle\Resolver;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Stn\StateMachineBundle\Annotation\State;
use Stn\StateMachineBundle\Callback\CallbackCollection;

/**
 * Callback configuration resolver
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
class CallbackResolver implements CallbackResolverInterface
{
	/**
	* @var \Symfony\Component\DependencyInjection\ContainerInterface
	*/
	private $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

	/**
	 * {@inheritdoc}
	 */
	public function resolve(State $state)
	{
		if ( ! is_array($cbConfigs = $state->getcallbacks())) {
			return $state;
		}

		$callbacks = $callback = null;

		foreach (array('before', 'after') as $position) {
			if (isset($cbConfigs[$position])) {
				$callbacks = $cbConfigs[$position];

				foreach ($callbacks as $key => $callback) {
					if (isset($callback['do']) && is_array($callback['do'])) {
						list($service, $cbName) = $callback['do'];

						$service = ltrim($service, '@');

						if (false === $this->container->has($service)) {
							throw new \InvalidArgumentException(sprintf('Service "%s" does not exist', $service));
						}

						$cbService = $this->container->get($service);

						$cbConfigs[$position][$key]['do'] = $cbService->get($cbName);
					}
				}
			}
		}

		$state->setCallbacks($cbConfigs);

		return $state;
	}
}
