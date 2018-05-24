<?php


namespace App\Authentication\Service;


use App\Authentication\Encoder\UserPasswordEncoder;
use App\Authentication\Repository\UserRepository;
use App\Authentication\User;
use App\Authentication\UserToken;
use App\Authentication\UserTokenInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var string
     */
    private $key;

    /**
     * AuthenticationService constructor.
     * @param UserRepository $userRepository
     * @param string $key
     * @param string $secret
     */
    public function __construct(UserRepository $userRepository, string $key)
    {
        $this->userRepository = $userRepository;
        $this->key = $key;
    }

    /**
     * Метод аутентифицирует пользователя на основании authentication credentials запроса
     *
     * @param string|mixed $credentials
     * @return UserTokenInterface
     */
    public function authenticate($credentials) : UserTokenInterface
    {
        if (!$credentials) {
            return new UserToken(null);
        }

        $credentials = base64_decode($credentials);

        $nonce = substr($credentials,0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($credentials,SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, strlen($credentials) - SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        try {
            $decr = json_decode(sodium_crypto_secretbox_open($cipher, $nonce, $this->key), true);
        }
        catch (\SodiumException $e) {
            return new UserToken(null);
        }

        if ($decr && key_exists('login', $decr) && key_exists('passwordHash', $decr)
            && strlen($nonce) == SODIUM_CRYPTO_SECRETBOX_NONCEBYTES
        ) {
            $user = $this->userRepository->findByLogin($decr['login']);

            if ($user && $user->getPassword() == $decr['passwordHash']) {
                return new UserToken($user);
            }
        }

        return new UserToken(null);
    }

    /**
     * Метод генерирует authentication credentials
     *
     * @param string $login
     * @param string $rawPassword
     * @return mixed
     * @throws \Exception
     */
    public function generateCredentials(string $login, string $rawPassword): string
    {
        $user = $this->userRepository->findByLogin($login);

        if ($user && password_verify($rawPassword, $user->getPassword())) {
            $cookieStructure = [
                'login'        => $user->getLogin(),
                'passwordHash' => $user->getPassword()
            ];

            // Using your key to encrypt information
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = sodium_crypto_secretbox(json_encode($cookieStructure), $nonce, $this->key);
            return base64_encode($nonce.$ciphertext);
        }
        //no such user or password mismatch
        return '';
    }

    /**
     * @param string $login
     * @param string $rawPassword
     * @return UserToken
     */
    public function registerUser(string $login, string $rawPassword) : UserToken
    {
        $cryptedPassword = UserPasswordEncoder::encodePassword($rawPassword);
        $user = new User(null, $login, $cryptedPassword);

        $userId = $this->userRepository->save($user);

        if ($userId) {
            return new UserToken(new User($userId, $login, $cryptedPassword));
        }

        return new UserToken(null);
    }
}