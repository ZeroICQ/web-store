<?php


namespace App\Authentication;


class UserInfo
{
    /**
     * @var int?
     */
    private $id;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $biography;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $secondName;
    /**
     * @var string
     */
    private $workPlace;

    /**
     * UserInfo constructor.
     * @param int $id
     * @param int $userId
     * @param string $biography
     * @param string $firstName
     * @param string $secondName
     * @param string $workPlace
     */
    public function __construct(?int $id, int $userId, string $biography, string $firstName, string $secondName, string $workPlace)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->biography = $biography;
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->workPlace = $workPlace;
    }


    /**
     * @return int?
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getBiography(): string
    {
        return $this->biography;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getSecondName(): string
    {
        return $this->secondName;
    }

    /**
     * @return string
     */
    public function getWorkPlace(): string
    {
        return $this->workPlace;
    }

}