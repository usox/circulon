<?php declare(strict_types=1);

namespace Usox\Circulon;

final class CirculonTest extends \PHPUnit\Framework\TestCase {

	private $circulon;

	public function setUp(): void {
		$this->circulon = new Circulon();
	}

	public function testAddDependencyFailsIfItemAlreadySet(): void {
		$this->expectException(
			Exception\DependencyEntryImmutableException::class,
			'Item `foo` already defined in dependency map'
		);

		$this->circulon
			->addDependency('foo', ['bar'])
			->addDependency('foo', ['baz']);
	}

	public function testResolveFailsOnCircularDependency(): void {
		$this->expectException(
			Exception\CircularDependencyException::class,
			'Circular reference for bar => foo'
		);

		$this->circulon
			->addDependency('foo', ['bar'])
			->addDependency('bar', ['foo']);

		$this->circulon->resolve();
	}

	public function testResolveFailsOnUnknownItem(): void {
		$this->expectException(
			Exception\DependencyItemNotFoundException::class,
			'Item `baz` not found in dependency map'
		);

		$this->circulon
			->addDependency('foo', ['bar'])
			->addDependency('bar', ['baz']);

		$this->circulon->resolve();
	}

	public function testResolveResolvesDependencies(): void {
		$this->circulon
			->addDependency('foo', ['bar'])
			->addDependency('bar', ['baz', 'foobar'])
			->addDependency('foobar', [])
			->addDependency('baz', ['foobar']);

		$this->assertSame(
			['foobar', 'baz', 'bar', 'foo'],
			$this->circulon->resolve()
		);
	}
}
