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
     * @return ManipleCore_Model_UserInterface|null
     */
    public function getUser($userId)
    {
        $userId = (int) $userId;
        $row = $this->_getUsersTable()->findRow($userId);
        if ($row) {
            return $this->createUser($row->toArray());
        }
        return null;
    }

    /**
     * @param  string $username
     * @return ManipleCore_Model_UserInterface|null
     */
    public function getUserByUsername($username)
    {
        $username = (string) $username;
        return $this->_getUserBy(array('username = LOWER(?)' => $username));
    }

    /**
     * @param  string $email
     * @return ManipleCore_Model_UserInterface|null
     */
    public function getUserByEmail($email)
    {
        $email = (string) $email;
        return $this->_getUserBy(array('email = LOWER(?)' => $email));
    }

    /**
     * @param  string $usernameOrEmail
     * @return ManipleCore_Model_UserInterface|null
     */
    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        $usernameOrEmail = (string) $usernameOrEmail;

        // usernames and emails are required to be stored lowercase only
        return $this->_getUserBy(array(
            'username = LOWER(?) OR email = LOWER(?)' => $usernameOrEmail,
        ));
    }

    /**
     * @param  array $userIds
     * @return ManipleCore_Model_UserInterface[]
     */
    public function getUsers(array $userIds = null)
    {
        $userIds = array_map('intval', $userIds);
        $users = array();

        if ($userIds) {
            $where = array('user_id IN (?)' => $userIds);
        } else {
            $where = null;
        }

        $rows = $this->_getUsersTable()->fetchAll($where);
        foreach ($rows as $row) {
            $user = $this->createUser($row->toArray());
            $users[$user->getId()] = $user;
        }

        return $users;
    }

    /**
     * Saves user entity to the storage.
     *
     * @param  ManipleCore_Model_UserInterface $user
     * @return ManipleCore_Model_UserInterface
     * @throws Exception
     */
    public function saveUser(ManipleCore_Model_UserInterface $user)
    {
        $userId = (int) $user->getId();

        if ($userId) {
            $row = $this->_getUsersTable()->findRow($userId);
        }

        if (empty($row)) {
            $row = $this->_getUsersTable()->createRow();
            $isCreate = true;
        } else {
            $isCreate = false;
        }

        $data = Zefram_Stdlib_ArrayUtils::changeKeyCase(
            $user->toArray(),
            Zefram_Stdlib_ArrayUtils::CASE_UNDERSCORE
        );

        if ($isCreate) {
            $data['user_id'] = null; // ensure auto incrementation
        }

        $row->setFromArray($data);
        $row->save();

        $user->setFromArray($row->toArray());

        return $user;
    }

    /**
     * Creates a new instance of user entity.
     *
     * @param  array $data OPTIONAL
     * @return ManipleCore_Model_UserInterface
     */
    public function createUser(array $data = null)
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

    /**
     * @param  string|array|Zend_Db_Expr $where
     * @return ManipleCore_Model_UserInterface|null
     */
    protected function _getUserBy($where)
    {
        $row = $this->_getUsersTable()->fetchRow($where);
        if ($row) {
            return $this->createUser($row->toArray());
        }
        return null;
    }
}
