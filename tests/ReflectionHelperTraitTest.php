<?php

namespace tests;

use dbx12\reflectionHelper\ReflectionHelperTrait;
use dbx12\reflectionHelper\ReflectionInfo;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class ReflectionHelperTraitTest extends TestCase
{

    /**
     * @dataProvider dataProvider_invokeMethod
     * @covers       \dbx12\reflectionHelper\ReflectionHelperTrait::invokeMethod()
     * @uses         \dbx12\reflectionHelper\ReflectionHelperTrait::processObjectOrClassFqn()
     */
    public function testInvokeMethod($objectOrFqn, $methodName, $args, $expectedValue, $expectException): void
    {
        $dummy = new Dummy();

        if ($expectException) {
            $this->expectException(ReflectionException::class);
        }
        $this->assertEquals(
            $expectedValue,
            $dummy->invokeMethod($objectOrFqn, $methodName, $args)
        );
        if ($expectException) {
            $this->fail('Expected exception was not thrown');
        }
    }

    public function dataProvider_invokeMethod(): array
    {
        return [
            'class method on object'     => [
                'objectOrFqn'     => new TestSubject(),
                'methodName'      => 'protectedFunction',
                'args'            => ['arg01'],
                'expectedValue'   => 'Class was called with arg01',
                'expectException' => false,
            ],
            'parent property on object'  => [
                'objectOrFqn'     => new TestSubject(),
                'methodName'      => 'parentProtectedFunction',
                'args'            => ['arg02'],
                'expectedValue'   => 'Parent was called with arg02',
                'expectException' => false,
            ],
            'unknown property on object' => [
                'objectOrFqn'     => new TestSubject(),
                'methodName'      => 'unknown',
                'args'            => ['arg03'],
                'expectedValue'   => null,
                'expectException' => true,
            ],
            'class property on fqn'      => [
                'objectOrFqn'     => TestSubject::class,
                'methodName'      => 'staticProtectedFunction',
                'args'            => ['arg04'],
                'expectedValue'   => 'Static class was called with arg04',
                'expectException' => false,
            ],
            'parent property on fqn'     => [
                'objectOrFqn'     => TestSubject::class,
                'methodName'      => 'staticParentProtectedFunction',
                'args'            => ['arg05'],
                'expectedValue'   => 'Static parent was called with arg05',
                'expectException' => false,
            ],
            'unknown property on fqn'    => [
                'objectOrFqn'     => TestSubject::class,
                'methodName'      => 'unknown',
                'args'            => ['arg06'],
                'expectedValue'   => null,
                'expectException' => true,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider_getInaccessibleProperty
     * @covers       \dbx12\reflectionHelper\ReflectionHelperTrait::getInaccessibleProperty()
     * @uses         \dbx12\reflectionHelper\ReflectionHelperTrait::getPropertyFromClassOrAncestors()
     * @uses         \dbx12\reflectionHelper\ReflectionHelperTrait::processObjectOrClassFqn()
     */
    public function testGetInaccessibleProperty($objectOrFqn, $propertyName, $expectedValue, $expectException): void
    {
        $dummy = new Dummy();

        if ($expectException) {
            $this->expectException(ReflectionException::class);
        }
        $this->assertEquals(
            $expectedValue,
            $dummy->getInaccessibleProperty($objectOrFqn, $propertyName)
        );
        if ($expectException) {
            $this->fail('Expected exception was not thrown');
        }
    }

    public function dataProvider_getInaccessibleProperty(): array
    {
        return [
            'class property on object'   => [
                'objectOrFqn'     => new TestSubject(),
                'propertyName'    => 'protectedProperty',
                'expectedValue'   => 'protected value',
                'expectException' => false,
            ],
            'parent property on object'  => [
                'objectOrFqn'     => new TestSubject(),
                'propertyName'    => 'parentProperty',
                'expectedValue'   => 'parentProperty value',
                'expectException' => false,
            ],
            'unknown property on object' => [
                'objectOrFqn'     => new TestSubject(),
                'propertyName'    => 'unknown',
                'expectedValue'   => null,
                'expectException' => true,
            ],
            'class property on fqn'      => [
                'objectOrFqn'     => TestSubject::class,
                'propertyName'    => 'staticProtectedProperty',
                'expectedValue'   => 'static protected value',
                'expectException' => false,
            ],
            'parent property on fqn'     => [
                'objectOrFqn'     => TestSubject::class,
                'propertyName'    => 'staticParentProperty',
                'expectedValue'   => 'static parentProperty value',
                'expectException' => false,
            ],
            'unknown property on fqn'    => [
                'objectOrFqn'     => TestSubject::class,
                'propertyName'    => 'unknown',
                'expectedValue'   => null,
                'expectException' => true,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider_setInaccessibleProperty
     * @covers       \dbx12\reflectionHelper\ReflectionHelperTrait::setInaccessibleProperty()
     * @uses         \dbx12\reflectionHelper\ReflectionHelperTrait::getPropertyFromClassOrAncestors()
     * @uses         \dbx12\reflectionHelper\ReflectionHelperTrait::processObjectOrClassFqn()
     * @uses         \dbx12\reflectionHelper\ReflectionHelperTrait::getInaccessibleProperty()
     * @throws \Exception should random_int glitch out
     */
    public function testSetInaccessibleProperty($objectOrFqn, $propertyName, $expectException): void
    {
        $dummy    = new Dummy();
        $newValue = random_int(0, 500);

        if ($expectException) {
            $this->expectException(ReflectionException::class);
        }
        $dummy->setInaccessibleProperty($objectOrFqn, $propertyName, $newValue);
        if ($expectException) {
            $this->fail('Expected exception was not thrown');
        }
        $this->assertEquals(
            $newValue,
            $dummy->getInaccessibleProperty($objectOrFqn, $propertyName),
            'New value is set'
        );
    }

    public function dataProvider_setInaccessibleProperty(): array
    {
        return [
            'class property on object'   => [
                'objectOrFqn'     => new TestSubject(),
                'propertyName'    => 'protectedProperty',
                'expectException' => false,
            ],
            'parent property on object'  => [
                'objectOrFqn'     => new TestSubject(),
                'propertyName'    => 'parentProperty',
                'expectException' => false,
            ],
            'unknown property on object' => [
                'objectOrFqn'     => new TestSubject(),
                'propertyName'    => 'unknown',
                'expectException' => true,
            ],
            'class property on fqn'      => [
                'objectOrFqn'     => TestSubject::class,
                'propertyName'    => 'staticProtectedProperty',
                'expectException' => false,
            ],
            'parent property on fqn'     => [
                'objectOrFqn'     => TestSubject::class,
                'propertyName'    => 'staticParentProperty',
                'expectException' => false,
            ],
            'unknown property on fqn'    => [
                'objectOrFqn'     => TestSubject::class,
                'propertyName'    => 'unknown',
                'expectException' => true,
            ],
        ];
    }

    /**
     * @covers \dbx12\reflectionHelper\ReflectionHelperTrait::processObjectOrClassFqn()
     * @throws \ReflectionException
     */
    public function testProcessObjectOrClassFqn(): void
    {
        $dummy  = new Dummy();
        $ref    = new ReflectionClass($dummy);
        $method = $ref->getMethod('processObjectOrClassFqn');
        $method->setAccessible(true);


        // invoke like with static classes
        $withExistingFqn = $method->invokeArgs($dummy, [TestSubject::class]);
        $this->assertInstanceOf(ReflectionInfo::class, $withExistingFqn, 'Calling with FQN returns ReflectionInfo');
        $this->assertEquals(TestSubject::class, $withExistingFqn->className, 'Class name set correctly');
        $this->assertNull($withExistingFqn->object, 'Object is set to null');
        $this->assertInstanceOf(ReflectionClass::class, $withExistingFqn->reflection, 'Reflection is set correctly');

        // invoke like with objects
        $instance          = new TestSubject();
        $withClassInstance = $method->invokeArgs($dummy, [$instance]);
        $this->assertInstanceOf(ReflectionInfo::class, $withClassInstance, 'Calling with class instance returns ReflectionInfo');
        $this->assertEquals(TestSubject::class, $withClassInstance->className, 'Class name set correctly');
        $this->assertEquals($withClassInstance->object, $instance, 'Instance is stored correctly');
        $this->assertInstanceOf(ReflectionClass::class, $withClassInstance->reflection, 'Reflection is set correctly');

        // expect an exception if neither object nor string are given as argument
        $this->expectException(\InvalidArgumentException::class);
        $method->invokeArgs($dummy, [1]);
        $this->fail('Expected exception was not thrown');
    }

    /**
     * @dataProvider dataProvider_getPropertyFromClassOrAncestors
     * @covers       \dbx12\reflectionHelper\ReflectionHelperTrait::getPropertyFromClassOrAncestors()
     * @param \ReflectionClass         $reflection
     * @param string                   $propertyName
     * @param \ReflectionProperty|null $expected
     * @param bool                     $expectException
     * @return void
     * @throws \ReflectionException
     */
    public function testGetPropertyFromClassOrAncestors(ReflectionClass $reflection, string $propertyName, ?ReflectionProperty $expected, bool $expectException): void
    {
        $dummy  = new Dummy();
        $ref    = new ReflectionClass($dummy);
        $method = $ref->getMethod('getPropertyFromClassOrAncestors');
        $method->setAccessible(true);

        if ($expectException) {
            $this->expectException(ReflectionException::class);
        }
        $actual = $method->invokeArgs($dummy, [$reflection, $propertyName]);
        if ($expectException) {
            $this->fail('Expected exception was not thrown');
        }
        $this->assertEquals($expected, $actual, 'Correct ReflectionProperty returned');
    }

    public function dataProvider_getPropertyFromClassOrAncestors(): array
    {
        return [
            'class property on object'       => [
                'reflection'      => new ReflectionClass(new TestSubject()),
                'propertyName'    => 'protectedProperty',
                'expected'        => new ReflectionProperty(TestSubject::class, 'protectedProperty'),
                'expectException' => false,
            ],
            'parent property on object'      => [
                'reflection'      => new ReflectionClass(new TestSubject()),
                'propertyName'    => 'parentProperty',
                'expected'        => new ReflectionProperty(TestSubject::class, 'parentProperty'),
                'expectException' => false,
            ],
            'nonexistent property on object' => [
                'reflection'      => new ReflectionClass(new TestSubject()),
                'propertyName'    => 'unknown',
                'expected'        => null,
                'expectException' => true,
            ],
            'class property on fqn'          => [
                'reflection'      => new ReflectionClass(TestSubject::class),
                'propertyName'    => 'staticProtectedProperty',
                'expected'        => new ReflectionProperty(TestSubject::class, 'staticProtectedProperty'),
                'expectException' => false,
            ],
            'parent property on fqn'         => [
                'reflection'      => new ReflectionClass(TestSubject::class),
                'propertyName'    => 'staticParentProperty',
                'expected'        => new ReflectionProperty(TestSubject::class, 'staticParentProperty'),
                'expectException' => false,
            ],
            'nonexistent property on class'  => [
                'reflection'      => new ReflectionClass(TestSubject::class),
                'propertyName'    => 'unknown',
                'expected'        => null,
                'expectException' => true,
            ],
        ];
    }
}
