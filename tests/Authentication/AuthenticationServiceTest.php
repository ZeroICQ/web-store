<?php


namespace AppTest\Authentication;


use App\Authentication\Repository\UserRepository;
use App\Authentication\Service\AuthenticationService;
use App\Authentication\User;
use App\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthenticationServiceTest extends TestCase
{
    public function testSuccessfullAuthenticate()
    {

        $session = $this->createMock(Session::class);
        $session->method('get')->willReturn('userName');
        $session->expects($this->once())
            ->method('get')->with($this->equalTo('login'));


        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByLogin')->willReturn(new User(1, 'userName', 'passhash'));

        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturn($repo);

        $auth = new AuthenticationService($em);
        $userToken = $auth->authenticate($session);

        $this->assertSame(1, $userToken->getUser()->getId());
        $this->assertSame('username', $userToken->getUser()->getLogin());
        $this->assertSame('passhash', $userToken->getUser()->getPassword());
        $this->assertFalse($userToken->isAnonymous());

    }

    public function testFailedAuthenticate()
    {

        $session = $this->createMock(Session::class);
        $session->method('get')->willReturn(null);

        $session->expects($this->once())
            ->method('get')->with($this->equalTo('login'));


        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByLogin')->willReturn(null);

        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturn($repo);

        $auth = new AuthenticationService($em);
        $userToken = $auth->authenticate($session);

        $this->assertNull($userToken->getUser());
        $this->assertTrue($userToken->isAnonymous());

    }

    public function testGenCredentialsUserNotNull()
    {
        $session = $this->createMock(Session::class);
        $session->method('invalidate');
        $session->expects($this->once())->method('invalidate');
        $session->method('set');
        $session->expects($this->once())->method('set')->with('login', 'username');

        $user = $this->createMock(User::class);
        $user->method('getLogin')->willReturn('username');
        $user->expects($this->once())->method('getLogin');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByLogin')->willReturn(null);

        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturn($repo);

        $auth = new AuthenticationService($em);
        $auth->generateCredentials($user, $session);
    }

    public function testGenCredentialsUserNull()
    {
        $session = $this->createMock(Session::class);
        $session->method('invalidate');
        $session->expects($this->once())->method('invalidate');
        $session->method('set');
        $session->expects($this->exactly(0))->method('set');

        $user = null;

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByLogin')->willReturn(null);

        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturn($repo);

        $auth = new AuthenticationService($em);
        $auth->generateCredentials($user, $session);
    }
}