<?php declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use Datto\JsonRpc\Exceptions\ApplicationException;
use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    public function indexAction(): array
    {
        $login = $this->dispatcher->getParam('login');
        $password = $this->dispatcher->getParam('password');
        if (!$login || !$password) {
            throw new ApplicationException('Invalid login or password', 1);
        }
        $row = User::findByLogin($login);
        if ($row === null) {
            throw new ApplicationException('Invalid login or password', 1);
        }

        if (!password_verify($password, $row->getPassword())) {
            throw new ApplicationException('Invalid login or password', 1);
        }

        return $row->toArray(['id', 'login']);
    }
}
