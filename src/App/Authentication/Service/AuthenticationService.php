<?php


namespace App\Authentication\Service;


use App\Authentication\Repository\UserRepository;
use App\Authentication\User;
use App\Authentication\UserInterface;
use App\Authentication\UserToken;
use App\Authentication\UserTokenInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @var string
     */
    private $secret;

    /**
     * AuthenticationService constructor.
     * @param UserRepository $userRepository
     * @param string $key
     * @param string $secret
     */
    public function __construct(UserRepository $userRepository, string $key, string $secret)
    {
        $this->userRepository = $userRepository;
        $this->key = $key;
        $this->secret = $secret;
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

        [$nonce, $cipher] = str_split($credentials, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        try {
            $decr = json_decode(sodium_crypto_secretbox_open($cipher, $nonce, $this->key));
        }
        catch (\SodiumException $e) {
            return new UserToken(null);
        }

        if ($decr && key_exists('user', $decr) && key_exists('secret', $decr) && $decr['secret'] == $this->secret &&
            strlen($nonce) == SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            $user = $this->userRepository->findByLogin($credentials->getLogin());
        } else {
            $user = null;
        }

        return new UserToken($user);
    }

    /**
     * Метод генерирует authentication credentials
     *
     * @param UserInterface $user
     * @return mixed
     */
    public function generateCredentials(UserInterface $user): string
    {
        $userFromDB = $this->userRepository->findByLogin($user->getLogin());

        if ($userFromDB && password_verify($user->getPassword(), $userFromDB->getPassword())) {
            $cookieStructure = [
                'user'   => $userFromDB->getLogin(),
                'secret' => $this->secret
            ];

            // Using your key to encrypt information
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = sodium_crypto_secretbox(json_encode($cookieStructure), $nonce, $this->key);
            return $nonce.$ciphertext;
        }

        return '';
    }
}