<?php declare(strict_types=1);

namespace app\test\JsonRpc;

use app\JsonRpc\Api;
use app\test\UnitTestCase;
use Datto\JsonRpc\Exceptions\ApplicationException;
use ReflectionMethod;

class ApiTest extends UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testEvaluate(): void
    {
        $dispatcherMock = $this->getMockBuilder(\Phalcon\DispatcherInterface::class)
            ->getMockForAbstractClass();
        $api = $this->getMockBuilder(Api::class)
            ->setConstructorArgs([
                $dispatcherMock
            ])
            ->setMethodsExcept(['evaluate'])
            ->getMock();
        $args = ['login' => 'user', 'password' => '12345'];
        $dispatcherMock->expects($this->once())->method('forward');
        $dispatcherMock->expects($this->once())->method('setParams')->with($args);
        $dispatcherMock->expects($this->once())->method('dispatch');
        $dispatcherMock->method('getReturnedValue')->willReturn([]);
        $result = $api->evaluate('controller.action', $args);
        $this->assertSame([], $result);
    }

    public function testValidateControllerAndAction(): void
    {
        $api = $this->getMockBuilder(Api::class)
            ->disableOriginalConstructor()
            ->getMock();
        $method = new ReflectionMethod(Api::class, 'validateControllerAndAction');
        $method->setAccessible(true);
        $method->invoke($api, 'myController.myTestAction');
        $this->assertTrue(true);
    }

    public function testValidateControllerAndActionException(): void
    {
        $this->expectException(ApplicationException::class);
        $api = $this->getMockBuilder(Api::class)
            ->disableOriginalConstructor()
            ->getMock();
        $method = new ReflectionMethod(Api::class, 'validateControllerAndAction');
        $method->setAccessible(true);
        $method->invoke($api, 'myTestAction');
    }

    public function testGetControllerAndAction()
    {
        $api = $this->getMockBuilder(Api::class)
            ->disableOriginalConstructor()
            ->getMock();
        $method = new ReflectionMethod(Api::class, 'getControllerAndAction');
        $method->setAccessible(true);
        $this->assertSame(
            [
                'controller' => 'app\controllers\MyController',
                'action' => 'myTestAction'
            ],
            $method->invoke($api, 'myController.myTestAction')
        );
    }
}
