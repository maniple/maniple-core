<?php

class ManipleCore_Model_UserRepository implements ManipleCore_Model_UserRepositoryInterface
{
    /**
     * @var string
     */
    protected $_userClass = 'ManipleCore_Model_User';

    /**
     * @var Zefram_Db_TableProvider
     */
    protected $_tableProvider;

    public function setUserClass($userClass)
    {
        $userClass = (string) $userClass;

        // can't use is_subclass_of as prior to PHP 5.3.7 it does not
        // check interfaces
        if (!in_array('ManipleCore_Model_UserInterface', class_implements($userClass))) {
            throw new InvalidArgumentException('User class must implement ManipleCore_Model_UserInterface interface');
        }

        $this->_userClass = $userClass;

        return $this;
    }

    /**
     * @param  Zefram_Db_TableProvider $tableProvider OPTIONAL
     * @return ManipleCore_Model_UserRepository
     */
    public function setTableProvider(Zefram_Db_TableProvider $tableProvider = null)
    {
        $this->_tableProvider = $tableProvider;
        return $this;
    }

    /**
     * @param  int $userId
     * @return ManipleCore_Model_UserInterface
     */
    public function getUser($userId)
    {
        $userId = (int) $userId;
        $row = $this->_getUsersTable()->findRow($userId);
        if ($row) {
            return $this->_createUser($row->toArray());
        }
        return null;
    }

    /**
     * @param  array $userIds
     * @return ManipleCore_Model_UserInterface[]
     */
    public function getUsers(array $userIds)
    {
        $userIds = array_map('intval', $userIds);
        $users = array();

        if ($userIds) {
            $rows = $this->_getUsersTable()->fetchAll(array('user_id IN (?)' => $userIds));
            foreach ($rows as $row) {
                $user = $this->_createUser($row->toArray());
                $users[$user->getId()] = $user;
            }
        }

        return $users;
    }

    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        $usernameOrEmail = (string) $usernameOrEmail;

        // usernames and emails are required to be stored lowercase only
        $row = $this->_getUsersTable()->fetchRow(array(
            'username = LOWER(?) OR email = LOWER(?)' => $usernameOrEmail,
        ));
        if ($row) {
            return $this->_createUser($row->toArray());
        }
        return null;
    }

    /**
     * @param  array $data
     * @return ManipleCore_Model_UserInterface
     */
    protected function _createUser(array $data)
    {
        $userClass = $this->_userClass;
        $user = new $userClass($data);
        return $user;
    }

    /**
     * @return ManipleCore_Model_DbTable_Users
     */
    protected function _getUsersTable()
    {
        return $this->_tableProvider->getTable('ManipleCore_Model_DbTable_Users');
    }
}
