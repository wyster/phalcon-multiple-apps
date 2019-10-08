<?php declare(strict_types=1);

use app\JsonRpc\Api;
use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exceptions\MethodException;
use Datto\JsonRpc\Server;
use Phalcon\Events\Event;

class IndexController extends \Phalcon\Mvc\Controller
{
    public function index()
    {
        $api = new Api($this->dispatcher);
        $server = new Server($api);

        if (!array_key_exists(0, $this->request->getPost())) {
            throw new \Datto\JsonRpc\Exceptions\ApplicationException('Content is empty');
        }
        return $server->reply($this->request->getPost()[0]);
    }
}
