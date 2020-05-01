<?php

namespace Leaf\Helpers\Tilly;

/**
Tilly:Function
------------------
Simple utility functions for function calls

@version 1.0
@author Michael Darko <mickdd22@gmail.com>
 */
class Func extends \Leaf\Helpers\Tilly {
	/**
	 * Invokes function after a couple of milliseconds
	 * 
	 * @param callable $func The function to call.
	 * @param int $wait The number of milliseconds to delay invocation.
	 * @param array $args Function arguments
	 * 
	 * @return mixed The return value from $func (callable)
	 */
	public function delay(callable $func, int $wait = 1, ...$args)
	{
		usleep($wait * 1000);

		return $func(...$args);
	}

	/**
	 * Creates a function that negates the result of the function
	 *
	 * @category Function
	 *
	 * @param callable $method The method to negate.
	 *
	 * @return callable Returns the new negated function.
	 */
	public function negate(callable $method): callable
	{
		return function() use ($method) {
			return !$method(...func_get_args());
		};
	}
}
