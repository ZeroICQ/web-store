<?php


namespace App\Authentication;


interface UserInfoInterface extends \JsonSerializable
{
    /**
     * @return int?
     */
    public function getId(): ?int;

    /**
     * @return int
     */
    public function getUserId(): int;

    /**
     * @return string
     */
    public function getBiography(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getSecondName(): string;

    /**
     * @return string
     */
    public function getWorkPlace(): string;

}