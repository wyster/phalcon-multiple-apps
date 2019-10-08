<?php declare(strict_types=1);

namespace app\test\controllers;

use app\test\UnitTestCase;
use Datto\JsonRpc\Exceptions\ApplicationException;
use Mockery;

/**
 * Class UnitTest
 */
class AuthControllerTest extends UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public static function invalidDataProvider(): array
    {
        return [
            [
                ['password' => '12345']
            ],
            [
                ['login' => 'admin']
            ],
            [
                ['login' => '']
            ],
            [
                ['login' => 'admin']
            ],
            [
                ['login' => 'admin', 'password' => '']
            ],
        ];
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testIndexAction(): void
    {
        $data = [
            'login' => 'admin',
            'password' => '123456'
        ];
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setParams($data);
        $this->di->setShared('dispatcher', $dispatcher);

        $mock = Mockery::mock('overload:app\models\User');
        $mock->shouldReceive('getPassword')->once()
            ->andReturn(password_hash($data['password'], PASSWORD_DEFAULT));
        $mock->shouldReceive('toArray')->once()->andReturn($data);
        $mock->shouldReceive('findByLogin')
            ->once()
            ->andReturn($mock);

        $controller = new \app\controllers\AuthController();
        $content = $controller->indexAction();
        $this->assertSame($data, $content);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testIndexActionLoginIsNullException(array $data): void
    {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionMessage('Invalid login or password');
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setParams($data);
        $this->di->setShared('dispatcher', $dispatcher);

        $controller = new \app\controllers\AuthController();
        $controller->indexAction();
    }


    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testIndexActionNotFoundInDb(): void
    {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionMessage('Invalid login or password');
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setParams([
            'login' => 'admin',
            'password' => '12345'
        ]);
        $this->di->setShared('dispatcher', $dispatcher);

        $mock = Mockery::mock('overload:app\models\User');
        $mock->shouldReceive('findByLogin')
            ->once()
            ->andReturn(null);

        $controller = new \app\controllers\AuthController();
        $controller->indexAction();
    }


    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testIndexActionInvalidPassword(): void
    {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionMessage('Invalid login or password');

        $data = [
            'login' => 'admin',
            'password' => '123456'
        ];
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setParams($data);
        $this->di->setShared('dispatcher', $dispatcher);

        $mock = Mockery::mock('overload:app\models\User');
        $mock->shouldReceive('getPassword')->once()
            ->andReturn($data['password']);
        $mock->shouldReceive('findByLogin')
            ->once()
            ->andReturn($mock);

        $controller = new \app\controllers\AuthController();
        $controller->indexAction();
    }
}
