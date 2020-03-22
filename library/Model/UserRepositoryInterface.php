<?php

/**
 * @deprecated
 */
interface ManipleCore_Model_UserRepositoryInterface
{
    public function getUser($userId);

    public function getUserByUsername($username);

    public function getUserByEmail($email);

    public function getUserByUsernameOrEmail($usernameOrEmail);

    public function getUsers(array $userIds = null);

    public function createUser(array $data = null);

    public function saveUser(ManipleCore_Model_UserInterface $user);
}
