<?php

namespace Leaf\Helpers\Tilly;

/**
Tilly:Collection
------------------
Simple utility functions for collections

@version 1.0
@author Michael Darko <mickdd22@gmail.com>
 */
class Collection extends \Leaf\Helpers\Tilly
{
	/**
	 * Split an array into groups of arrays holding a number of values
	 * 
	 * @param array $array The array to split
	 * @param int $number The number of characters in a sub array(split)
	 * 
	 * @return array The "chunk-ed" array
	 */
	public function chunk(array $array, int $number): array
	{
		if ($number < 1) {
			return [];
		}

		return array_chunk($array ?? [], $number, false);
	}

	/**
	 * Creates an array with all falsey values(`false`, `null`, `0`, `""`, `undefined`, and `NaN`) removed
	 * 
	 * @param array $array The array to compact.
	 * 
	 * @return array The "compact-ed" array
	 */
	public function compact(array $array): array
	{
		return array_values(array_filter($array ?? []));
	}

	/**
	 * Merges items into an array
	 * 
	 * @param array $array The array to merge into.
	 * @param array $values The values to concatenate.
	 * 
	 * @return array The "concat-enated" array
	 */
	public function concat(array $array, ...$values): array
	{
		$check = function ($value): array {
			return is_array($value) ? $value : [$value];
		};

		return array_merge($check($array), ...array_map($check, $values));
	}

	/**
	 * Creates an array of unique values in different arrays.
	 * The order and references of result values are determined by the first array.
	 * 
	 * @param array $array The array to inspect.
	 * @param array …$values The values to exclude.
	 * 
	 * @return array The "compact-ed" array
	 */
	public function difference(array $array, array ...$values): array
	{
		return array_values(array_diff($array, ...$values));
	}

	/**
	 * Slices items from the beginning of an array
	 * 
	 * @param array $array The array to slice.
	 * @param array …$values The number of items to drop.
	 * 
	 * @return array The sliced array
	 */
	public function drop_left(array $array, int $n = 1): array
	{
		return array_slice($array, $n);
	}

	/**
	 * Creates an array slice excluding elements dropped from the start.
	 * 
	 * @param array $array The array to slice.
	 * @param callable $method The function invoked per iteration.
	 *
	 * @return array the slice of `array`.
	 */
	public function drop_left_if(array $array, callable $method): array
	{
		reset($array);
		$count = count($array);
		$length = 0;
		$index = key($array);

		while ($length <= $count && $method($array[$index], $index, $array)) {
			array_shift($array);
			\reset($array);
			$length++;
			$index = \key($array);
		}

		return $array;
	}

	/**
	 * Slices items from the beginning of an array
	 * 
	 * @param array $array The array to slice.
	 * @param array …$values The number of items to drop.
	 * 
	 * @return array The sliced array
	 */
	public function drop_right(array $array, int $n = 1): array
	{
		$count = count($array);

		if ($n > $count) {
			$n = $count;
		}

		return array_slice($array, 0, $count - $n);
	}

	/**
	 * Creates an array slice excluding elements dropped from the end.
	 * 
	 * @param array $array The array to slice.
	 * @param callable $method The function invoked per iteration.
	 *
	 * @return array the slice of `array`.
	 */
	public function drop_right_if(array $array, callable $method): array
	{
		end($array);
		$length = count($array);
		$index = key($array);

		while ($length && $method($array[$index], $index, $array)) {
			array_pop($array);
			$length--;
			end($array);
			$index = key($array);
		}

		return $array;
	}

	/**
	 * Return the first element of an array
	 * 
	 * @param array $array Array to index
	 * 
	 * @return mixed First element of the array
	 */
	public function first(array $array)
	{
		reset($array);
		return current($array) ?: null;
	}

	/**
	 * Recursively flattens an array.
	 *
	 * @param array $array The array to flatten.
	 *
	 * @return array Returns the new flattened array.
	 */
	public function flatten(array $array): array
	{
		return $this->baseFlatten($array, PHP_INT_MAX);
	}

	/**
	 * Recursively flattens an array to a certain depth.
	 *
	 * @param array $array The array to flatten.
	 * @param int $depth The depth to flatten to.
	 *
	 * @return array Returns the new flattened array.
	 */
	public function flatten_to(array $array, int $depth = 1): array
	{
		return$this-> baseFlatten($array, $depth);
	}

	/**
	 * Get the element at a particular index in an array
	 * 
	 * @param array $array The array to query.
	 * @param int $index The index of the element to return.
	 * 
	 * @return mixed The element at position index
	 */
	public function get(array $array, int $index) {
		return array_values($array)[$index < 0 ? count($array) + $index : $index] ?? null;
	}

	/**
	 * Create an array of unique values that are included in all given arrays
	 * 
	 * @param array $arrays
	 *
	 * @return array the array of intersecting values.
	 */
	public function intersects(array ...$arrays): array
	{
		return array_intersect(...$arrays);
	}

	/**
	 * Create an array of unique values, in order, from all given arrays.
	 *
	 * @param array $arrays The arrays to inspect.
	 *
	 * @return array the new array of combined values.
	 */
	public function join(array ...$arrays): array
	{
		return array_unique(array_merge(...$arrays));
	}

	/**
	 * Return the last element of an array
	 * 
	 * @param array $array Array to index
	 * 
	 * @return mixed Last element of the array
	 */
	public function last(array $array)
	{
		return end($array) ?: null;
	}

	/**
	 * Remove any element from an array if the callable returns true. **Directly mutates array**
	 * 
	 * @param array $array The array to modify.
	 * @param callable $method The function invoked per iteration.
	 * 
	 * @return array Array with removed items
	 */
	public function remove(array &$array, callable $method): array
	{
		$resultArray = [];
		$array = array_filter($array, function ($val, $key) use ($method, $array, &$resultArray) {
			$result = $method($val, $key, $array);

			if ($result) {
				$resultArray[] = $val;
			}

			return !$result;
		}, ARRAY_FILTER_USE_BOTH);

		$array = array_values($array); // Re-index array

		return $resultArray;
	}

	/**
	 * Slice an array from `start` up to, but not including, `end`.
	 *
	 * @param array $array The array to slice.
	 * @param int $start The start position.
	 * @param int $end The end position.
	 *
	 * @return array the sliced array
	 */
	public function slice(array $array, int $start, int $end = null): array
	{
		return array_slice($array, $start, $end);
	}

	/**
	 * Get an array of unique values(no duplicates) from an array.
	 *
	 * @param array $array The array to inspect.
	 *
	 * @return array the new duplicate free array
	 */
	public function uniques(array $array = []): array
	{
		return array_unique($array);
	}
}
