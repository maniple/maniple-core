<?php

interface ManipleCore_Model_UserRepositoryInterface
{
    public function getUser($userId);

    public function getUsers(array $userIds);
}
