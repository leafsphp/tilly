<?php

namespace Leaf\Helpers;

/**
Tilly
------------------
A simple utility toolkit for PHP

@version 1.0
@author Michael Darko <mickdd22@gmail.com>
*/
class Tilly {
	public function arrayPush(&$array, $values)
	{
		$index = -1;
		$length = \is_array($values) ? \count($values) : \strlen($values);
		$offset = \count($array);

		while (++$index < $length) {
			$array[$offset + $index] = $values[$index];
		}

		return $array;
	}

	public function isFlattenable($value): bool
	{
		return \is_array($value) && ([] === $value || \range(0, \count($value) - 1) === \array_keys($value));
	}

	public function baseFlatten(?array $array, int $depth, callable $method = null, bool $isStrict = null, array $result = null): array
	{
		$result = $result ?? [];

		if ($array === null) {
			return $result;
		}

		$method = $method ?? '$this->isFlattenable';

		foreach ($array as $value) {
			if ($depth > 0 && $method($value)) {
				if ($depth > 1) {
					// Recursively flatten arrays (susceptible to call stack limits).
					$result = $this->baseFlatten($value, $depth - 1, $method, $isStrict, $result);
				} else {
					$this->arrayPush($result, $value);
				}
			} elseif (!$isStrict) {
				$result[\count($result)] = $value;
			}
		}

		return $result;
	}
}