<?php


namespace AppTest\Authentication;


use App\Authentication\Encoder\UserPasswordEncoder;
use App\Authentication\Repository\UserRepository;
use App\Authentication\Service\AuthenticationService;
use App\Authentication\User;
use PHPUnit\Framework\TestCase;

class AuthenticationServiceTest extends TestCase
{
    public function testSuccessfulAuthenticate()
    {
        $cryptedPassword = UserPasswordEncoder::encodePassword('passhash');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByLogin')->willReturn(
            new User(1, 'username', $cryptedPassword));
        $repo->expects($this->exactly(2))
            ->method('findByLogin')->with($this->equalTo('username'));

        $auth = new AuthenticationService($repo, 'iopdasojijcoajscx,mzmc,z.xmizqje');
        $credentials = $auth->generateCredentials('username', 'passhash');
        $userToken = $auth->authenticate($credentials);

        $this->assertFalse($userToken->isAnonymous());
        $this->assertSame(1, $userToken->getUser()->getId());
        $this->assertSame('username', $userToken->getUser()->getLogin());
        $this->assertSame($cryptedPassword, $userToken->getUser()->getPassword());

    }

    public function testFailedAuthenticate()
    {
        $cryptedPassword = UserPasswordEncoder::encodePassword('passhash');

        $repo = $this->createMock(UserRepository::class);
        $repo->method('findByLogin')->willReturn(
            new User(1, 'username', $cryptedPassword));
        $repo->expects($this->exactly(2))
            ->method('findByLogin')->with($this->equalTo('username'));

        $auth = new AuthenticationService($repo, 'iopdasojijcoajscx,mzmc,z.xmizqje');
        $credentials = $auth->generateCredentials('username', 'wrongpassword');
        $userToken = $auth->authenticate($credentials);

        $this->assertTrue($userToken->isAnonymous());
        $this->assertNull($userToken->getUser());
    }

    public function testRegisterUser()
    {
        $repo = $this->createMock(UserRepository::class);

        $repo->method('save')->willReturn(1);
        $repo->expects($this->once())
            ->method('save')->with($this->equalTo(1));

        $auth = new AuthenticationService($repo, 'iopdasojijcoajscx,mzmc,z.xmizqje');
        $auth->registerUser('username', 'password');

    }

}