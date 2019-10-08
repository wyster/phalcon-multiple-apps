<?php declare(strict_types=1);

namespace app\test\JsonRpc;

use app\JsonRpc\Api;
use Datto\JsonRpc\Exceptions\ApplicationException;
use PHPUnit\Framework\TestCase;
use app\test\UnitTestCase;
use ReflectionMethod;

class ApiTest extends UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testEvaluate(): void
    {
        $this->assertTrue(true);
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
