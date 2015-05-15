<?php

namespace Stn\StateMachineBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * Annotation for configure state of entity
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @see https://github.com/yohang/Finite/blob/master/examples/multiple-graphs.php
 *
 * @author Santino Wu <santinowu.wsq@gmail.com>
 */
class State extends ConfigurationAnnotation
{
	/**
	 * @var string The state entity
	 */
	private $class;

	/**
	 * @var string The state graph
	 */
	private $graph;

	/**
	 * @var string The state entity property which will be serialized
	 */
	private $propertyPath;

	/**
	 * @var array Available state list
	 */
	private $states;

	/**
	 * @var array List of transition which state can be translated
	 */
	private $transitions;

	/**
	 * @var array List of callbacks
	 */
	private $callbacks;

	/**
	 * Get state target
	 *
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * Set state target
	 *
	 * @param string $class
	 */
	public function setClass($class)
	{
		$this->class = (string) $class;
	}

	/**
	* Get state graph
	*
	* @return string
	*/
	public function getGraph()
	{
		return $this->graph;
	}

	/**
	 * Set state graph
	 *
	 * @param string $class
	 */
	public function setGraph($graph)
	{
		$this->graph = (string) $graph;
	}

	/**
	 * Get the state entity property which will be serialized
	 *
	 * @return string
	 */
	public function getPropertyPath()
	{
		return $this->propertyPath;
	}

	/**
	 * Set the state entity property which will be serialized
	 *
	 * @param string $propertyPath
	 */
	public function setPropertyPath($propertyPath)
	{
		$this->propertyPath = (string) $propertyPath;
	}

	/**
	 * Get available state list
	 *
	 * @return array
	 */
	public function getStates()
	{
		return $this->states;
	}

	/**
	 * Set available state list
	 *
	 * @param array $states
	 */
	public function setStates(array $states)
	{
		$this->states = $states;
	}

	/**
	 * Get list of transition which state can be translated
	 *
	 * @return array
	 */
	public function getTransitions()
	{
		return $this->transitions;
	}

	/**
	 * Set list of transition which state can be translated
	 *
	 * @param array $transitions
	 */
	public function setTransitions(array $transitions)
	{
		$this->transitions = $transitions;
	}

	/**
	 * Get list of callback
	 *
	 * @return array
	 */
	public function getCallbacks()
	{
		return $this->callbacks;
	}

	/**
	 * Set list of callback
	 *
	 * @param array $callbacks
	 */
	public function setCallbacks(array $callbacks)
	{
		$this->callbacks = $callbacks;
	}

	/**
	 * Convert configuration to array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'class' => $this->class,
			'graph' => $this->graph,
			'property_path' => $this->propertyPath,
			'states' => $this->states,
			'transitions' => $this->transitions,
			'callbacks' => $this->callbacks,
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAliasName()
	{
		return 'stn_state';
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowArray()
	{
		return true;
	}
}
