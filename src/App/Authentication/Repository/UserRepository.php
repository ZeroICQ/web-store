<?php


namespace App\Authentication\Repository;


use App\Authentication\User;
use App\Authentication\UserInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * Метод ищет пользователя по индентификатору, возвращает UserInterface если пользователь существует, иначе null
     *
     * @param int $id
     * @return UserInterface|null
     */
    public function findById(int $id): ?UserInterface
    {
        $stmt = $this->conn->prepare("SELECT id, login, password, salt FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $stmt->bind_result($id, $login, $password, $salt);
        $stmt->fetch();

        if ($stmt->num_rows === 0) {
            $user = null;
        } else {
            $user = new User($id, $login, $password, $salt);
        }

        $stmt->close();

        return $user;
    }

    /**
     * Метод ищет пользователя по login, возвращает UserInterface если пользователь существует, иначе null
     *
     * @param string $login
     * @return UserInterface|null
     */
    public function findByLogin(string $login): ?UserInterface
    {
        // TODO: Implement findByLogin() method.
    }

    /**
     * Метод сохраняет пользоваля в хранилище
     *
     * @param UserInterface $user
     */
    public function save(UserInterface $user)
    {
        $stmt = $this->conn->prepare("INSERT INTO users(login, password) values(?,?)");
        $login = $user->getLogin();
        $pass = $user->getPassword();

        $stmt->bind_param("ss", $login, $pass);
        $stmt->execute();
        $stmt->close();
    }
}