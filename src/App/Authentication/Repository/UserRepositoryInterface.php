<?php

namespace App\Authentication\Repository;

use App\Authentication\UserInterface;

/**
 * Domain and data mapping layer
 * @see http://design-pattern.ru/patterns/repository.html
 *
 * Interface UserRepositoryInterface
 * @package App\Identification\Repository
 */
interface UserRepositoryInterface
{
	/**
	 * Метод ищет пользователя по индентификатору, возвращает UserInterface если пользователь существует, иначе null
	 *
	 * @param int $id
	 * @return UserInterface|null
	 */
	public function findById(int $id): ?UserInterface;

	/**
	 * Метод ищет пользователя по login, возвращает UserInterface если пользователь существует, иначе null
	 *
	 * @param string $login
	 * @return UserInterface|null
	 */
	public function findByLogin(string $login): ?UserInterface;

	/**
	 * Метод сохраняет пользоваля в хранилище
	 *
	 * @param UserInterface $user
	 */
	public function save(UserInterface $user);
}