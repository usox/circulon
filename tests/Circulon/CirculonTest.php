<?php
namespace Usox\Circulon;

final class CirculonTest extends \PHPUnit_Framework_TestCase {

	private $circulon;

	public function setUp() {
		$this->circulon = new Circulon();
	}

	public function testAddDependencyFailsIfItemAlreadySet() {
		$this->setExpectedException(
			Exception\DependencyEntryImmutableException::class,
			'Item `foo` already defined in dependency map'
		);

		$this->circulon
			->addDependency('foo', 'bar')
			->addDependency('foo', 'baz');
	}

	public function testResolveFailsOnCircularDependency() {
		$this->setExpectedException(
			Exception\CircularDependencyException::class,
			'Circular reference for bar => foo'
		);

		$this->circulon
			->addDependency('foo', 'bar')
			->addDependency('bar', 'foo');

		$this->circulon->resolve();
	}

	public function testResolveFailsOnUnknownItem() {
		$this->setExpectedException(
			Exception\DependencyItemNotFoundException::class,
			'Item `baz` not found in dependency map'
		);

		$this->circulon
			->addDependency('foo', 'bar')
			->addDependency('bar', 'baz');

		$this->circulon->resolve();
	}

	public function testResolveResolvesDependencies() {
		$this->circulon
			->addDependency('foo', 'bar')
			->addDependency('bar', ['baz', 'foobar'])
			->addDependency('foobar', [])
			->addDependency('baz', ['foobar']);

		$this->assertSame(
			['foobar', 'baz', 'bar', 'foo'],
			$this->circulon->resolve()
		);
	}
}
