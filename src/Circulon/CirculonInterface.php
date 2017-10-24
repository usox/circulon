<?php
namespace Usox\Circulon;

/**
 * Interface: CirculonInterface
 */
interface CirculonInterface {

	/**
	 * @param string $key
	 * @param string|string[] $dependency
	 *
	 * @return $this
	 */
	public function addDependency($key, $dependency);

	/**
	 * @return string[]
	 */
	public function resolve();
}
