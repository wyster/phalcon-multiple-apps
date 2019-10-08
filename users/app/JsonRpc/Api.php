<?php declare(strict_types=1);

namespace app\JsonRpc;

use Datto\JsonRpc\Evaluator;

class Api implements Evaluator
{
    /**
     * @var \Phalcon\Dispatcher
     */
    private $dispatcher;

    public function __construct(\Phalcon\Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function evaluate($method, $arguments)
    {
        $methodAndController = explode('.', $method);
        if (count($methodAndController) !== 2) {
            throw new \Datto\JsonRpc\Exceptions\MethodException('invalid');
        }
        $this->dispatcher->forward([
            'controller' => $methodAndController[0],
            'action' => $methodAndController[1],
        ]);
        $this->dispatcher->setParams($arguments);
        try {
            $this->dispatcher->dispatch();
        } catch (\Throwable $e) {
            throw new \Datto\JsonRpc\Exceptions\ApplicationException($e->getMessage(), $e->getCode());
        }
        return $this->dispatcher->getReturnedValue();
    }
}
