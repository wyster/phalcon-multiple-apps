<?php declare(strict_types=1);

use Datto\JsonRpc\Exceptions\MethodException;
use Phalcon\Db\AdapterInterface;
use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    /**
     * @return AdapterInterface
     */
    private function getDb(): AdapterInterface
    {
        return $this->di->get('db');
    }

    public function indexAction()
    {
        $login = $this->dispatcher->getParam('login');
        $password = $this->dispatcher->getParam('password');
        /**
         * @var app\models\User|false $row
         */
        $row = \app\models\User::findByLogin($login);
        if ($row === null) {
            throw new \Datto\JsonRpc\Exceptions\ApplicationException('User not found', 1);
        }

        if (!password_verify($password, $row->password)) {
            throw new \Datto\JsonRpc\Exceptions\ApplicationException('Invalid login or password', 1);
        }

        return $row->toArray(['id', 'login']);
    }
}
