<?php declare(strict_types=1);

namespace Usox\Circulon;

final class Circulon implements CirculonInterface {

	/**
	 * @var array
	 */
	private $dependency_map = [];

	/**
	 * @var string[]
	 */
	private $unresolved_dependencies = [];

	/**
	 * @var string[]
	 */
	private $resolved_dependencies = [];

	public function addDependency(string $key, array $dependency): CirculonInterface {
		if (array_key_exists((string) $key, $this->dependency_map)) {
			throw new Exception\DependencyEntryImmutableException(
				sprintf('Item `%s` already defined in dependency map', $key)
			);
		}

		$this->dependency_map[(string) $key] = $dependency;

		return $this;
	}

	public function resolve(): array {
		foreach (array_keys($this->dependency_map) as $item) {
			$this->deepResolve($item);
		}

		return $this->resolved_dependencies;
	}


	private function deepResolve(string $item): void {
		if (!array_key_exists($item, $this->dependency_map)) {
			throw new Exception\DependencyItemNotFoundException(
				sprintf('Item `%s` not found in dependency map', $item)
			);
		}

		array_push($this->unresolved_dependencies, $item);

		foreach ($this->dependency_map[$item] as $dependency) {
			if (!in_array($dependency, $this->resolved_dependencies)) {
				if (!in_array($dependency, $this->unresolved_dependencies)) {
					array_push($this->unresolved_dependencies, $dependency);
					$this->deepResolve($dependency);
				} else {
					throw new Exception\CircularDependencyException(
						sprintf('Circular reference for %s => %s', $item, $dependency)
					);
				}
			}
		}

		if (false === in_array($item, $this->resolved_dependencies)) {
			array_push($this->resolved_dependencies, $item);
		}

		if (($index = array_search($item, $this->unresolved_dependencies)) !== false) {
			unset($this->unresolved_dependencies[$index]);
		}
	}
}
