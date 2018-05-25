<?php


namespace App\Authentication\Repository;


use App\Authentication\UserInfo;

class UserInfoRepository extends BaseRepository
{
    /**
     * @param int $userId
     * @return int
     */
    public function saveEmpty(int $userId): int
    {
        $insert_id = $this->db->insert('user_info', ['user_id' => $userId], 'i');

        return $insert_id;
    }

    /**
     * @param int $userId
     * @return UserInfo|null
     */
    public function getInfo(int $userId): ?UserInfo
    {
        $res = $this->db->selectFirstSimpleEqCond('user_info',
            ['biography', 'first_name', 'second_name', 'work_place'],
            'user_id', $userId, 'i');

        if (!$res) {
            return null;
        }

        return new UserInfo(
            null,
            $userId,
            ...array_values($res)
        );
    }

    /**
     * @param int $userId
     * @param string $firstName
     * @param string $secondName
     * @param string $workPlace
     * @param string $biography
     * @return bool
     */
    public function updateInfo(int $userId, string $firstName, string $secondName, string $workPlace, string $biography): bool
    {
        return $this->db->update('user_info',[
            'first_name'  => $firstName,
            'second_name' => $secondName,
            'work_place'  => $workPlace,
            'biography'   => $biography
        ], 'user_id', $userId, 'ssssi');
    }

}