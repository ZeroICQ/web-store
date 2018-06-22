<?php


namespace App\Authentication\Repository;



use App\Authentication\UserInfoInterface;

interface UserInfoRepositoryInterface
{
    /**
     * @param int $userId
     * @return int
     */
    public function saveEmpty(int $userId): int;

    /**
     * @param int $userId
     * @return UserInfoInterface|null
     */
    public function getInfo(int $userId): ?UserInfoInterface;

    /**
     * @param int $userId
     * @param string $firstName
     * @param string $secondName
     * @param string $workPlace
     * @param string $biography
     * @return bool
     */
    public function updateInfo(int $userId, string $firstName, string $secondName, string $workPlace, string $biography): bool;
}
