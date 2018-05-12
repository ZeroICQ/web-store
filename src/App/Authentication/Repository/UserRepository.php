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
        $result = $this->db->selectFirstSimpleEqCond('users', ['login, password'], 'id', $id, 'i');

        $user = null;
        if ($result) {
            $user = new User($id, $result['login'], $result['password']);
        }

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

        $result = $this->db->selectFirstSimpleEqCond('users', ['id, password'], 'login', $login, 's');


        $user = null;
        if ($result) {
            $user = new User($result['id'], $login, $result['password']);
        }

        return $user;
    }

    /**
     * Метод сохраняет пользоваля в хранилище
     *
     * @param UserInterface $user
     * @return int
     */
    public function save(UserInterface $user): int
    {
        $insert_id = $this->db->insert('users', ['login' => $user->getLogin(), 'password' => $user->getPassword()], 'ss');
        $user->setId($insert_id);

        return $insert_id;
    }
}