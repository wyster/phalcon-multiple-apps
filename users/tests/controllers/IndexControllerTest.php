<?php declare(strict_types=1);

namespace Test\Controllers;

use Phalcon\Mvc\View;
use Test\UnitTestCase;

/**
 * Class UnitTest
 */
class IndexControllerTest extends UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testIndexAction(): void
    {
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $this->di->setShared('dispatcher', $dispatcher);

        $request = new \Phalcon\Http\Request();
        $this->di->setShared('request', $request);
        $controller = new \app\controllers\IndexController();
        $content = $controller->indexAction();
        $this->assertSame(
            '{"jsonrpc":"2.0","id":null,"error":{"code":-32700,"message":"Parse error"}}',
            $content
        );
    }
}
