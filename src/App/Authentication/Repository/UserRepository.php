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
        $stmt = $this->conn->prepare("SELECT login, password FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $login = null;
        $password = null;

        $stmt->bind_result($login, $password);

        $user = null;
        if ($stmt->fetch()) {
            $user = new User($id, $login, $password);
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
        $login = strtolower($login);

        $stmt = $this->conn->prepare("SELECT id, password FROM users WHERE login = ?");
        $stmt->bind_param('s', $login);
        $stmt->execute();

        $id = null;
        $password = null;

        $stmt->bind_result($id,$password);


        if (!$stmt->fetch()) {
            $user = null;
        } else {
            $user = new User($id, $login, $password);
        }

        $stmt->close();

        return $user;
    }

    /**
     * @param string $login
     * @param string $rawPassword
     * @return UserInterface|null
     */
    public function findByLoginPassword(string $login, string $rawPassword): ?UserInterface
    {
        $login = strtolower($login);
        $user = $this->findByLogin($login);

        if ($user && password_verify($rawPassword, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    /**
     * Метод сохраняет пользоваля в хранилище
     *
     * @param UserInterface $user
     * @return array
     */
    public function save(UserInterface $user) : array
    {
        $stmt = $this->conn->prepare("INSERT INTO users(login, password) values(?, ?)");
        $login = $user->getLogin();
        $pass = $user->getPassword();

        $stmt->bind_param("ss", $login, $pass);
        $stmt->execute();
        $errors = $stmt->error_list;

        $user->setId($this->conn->insert_id);

        $stmt->close();
        return $errors;
    }
}