<?php declare(strict_types=1);

namespace app\JsonRpc;

use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exceptions\ApplicationException;
use function count;

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

    public function evaluate($method, $arguments): array
    {
        $this->validateControllerAndAction($method);
        $this->dispatcher->forward($this->getControllerAndAction($method));
        $this->dispatcher->setParams($arguments);
        try {
            $this->dispatcher->dispatch();
        } catch (\Throwable $e) {
            error_log($e->__toString());
            throw new ApplicationException($e->getMessage(), $e->getCode());
        }
        return $this->dispatcher->getReturnedValue();
    }

    private function validateControllerAndAction(string $method): void
    {
        $methodAndController = explode('.', $method);
        if (count($methodAndController) !== 2) {
            throw new ApplicationException(
                'Invalid controller and action name, need use `controllerName.actionName`',
                1
            );
        }
    }

    private function getControllerAndAction(string $method): array
    {
        $methodAndController = explode('.', $method);
        return [
            'controller' => 'app\controllers\\' . ucfirst($methodAndController[0]),
            'action' => $methodAndController[1],
        ];
    }
}
