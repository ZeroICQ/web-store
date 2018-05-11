<?php


namespace App\Authentication\Service;


use App\Authentication\Encoder\UserPasswordEncoder;
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

        $credentials = base64_decode($credentials);

        $nonce = substr($credentials,0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = substr($credentials,SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, strlen($credentials) - SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
//        [$nonce, $cipher] = str_split(base64_decode($credentials), SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        try {
            $decr = json_decode(sodium_crypto_secretbox_open($cipher, $nonce, $this->key), true);
        }
        catch (\SodiumException $e) {
            return new UserToken(null);
        }

        if ($decr && key_exists('user', $decr) && key_exists('secret', $decr) && $decr['secret'] == $this->secret &&
            strlen($nonce) == SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            $user = $this->userRepository->findByLogin($decr['user']);
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
            return base64_encode($nonce.$ciphertext);
        }
        return '';
    }

    /**
     * @param UserInterface $user
     * @return UserToken
     */
    public function registerUser(UserInterface $user) : UserToken
    {
        $rawPass = $user->getPassword();
        $user->setPassword(UserPasswordEncoder::encodePassword($user->getPassword()));
        $this->userRepository->save($user);

        $user->setPassword($rawPass);
        return new UserToken($user);
    }
}