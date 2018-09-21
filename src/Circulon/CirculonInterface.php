<?php declare(strict_types=1);

namespace Usox\Circulon;

interface CirculonInterface {

	public function addDependency(string $key, array $dependency): Circuloninterface;

	public function resolve(): array;
}
