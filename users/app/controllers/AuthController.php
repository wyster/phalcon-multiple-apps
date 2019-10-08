<?php declare(strict_types=1);

use Datto\JsonRpc\Exceptions\MethodException;
use Phalcon\Db\AdapterInterface;
use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
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

        if (!password_verify($password, $row->getPassword())) {
            throw new \Datto\JsonRpc\Exceptions\ApplicationException('Invalid login or password', 1);
        }

        return $row->toArray(['id', 'login']);
    }
}
