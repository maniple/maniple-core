<?php

/**
 * Zend_Db_Table based user repository.
 *
 * @package ManipleCore_Model
 * @version 2014-07-05
 * @uses    Zend_Db_Table
 * @uses    Zefram_Stdlib
 * @deprecated
 */
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

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;

    /**
     * @param  string $userClass
     * @return ManipleCore_Model_UserRepository
     */
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
     * @param  Zend_Cache_Core $cache
     * @return ManipleCore_Model_UserRepository
     */
    public function setCache(Zend_Cache_Core $cache = null)
    {
        $this->_cache = $cache;
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
            return $this->_setUserFromRow($this->createUser(), $row);
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
        $users = array();

        if ($userIds) {
            $userIds = array_map('intval', $userIds);
            $where = array('user_id IN (?)' => $userIds);
        } else {
            $where = null;
        }

        $rows = $this->_getUsersTable()->fetchAll($where);
        foreach ($rows as $row) {
            $user = $this->_setUserFromRow($this->createUser(), $row);
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
        $userId = $user->getId();

        if ($userId) {
            $row = $this->_getUsersTable()->findRow((int) $userId);
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
            // disallow explicitly setting value on auto increment column, as
            // in some DBMS write may fail if sequence reaches value that is
            // already present in the table
            $sequence = $row->getTable()->info(Zend_Db_Table_Abstract::SEQUENCE);
            foreach ($row->getPrimaryKey() as $column => $value) {
                if ($sequence === true || $sequence === $column) {
                    unset($data[$column]);
                }
            }
        }

        $row->setFromArray($data);
        $row->save();

        return $this->_setUserFromRow($user, $row);
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
     * @param  string|array|Zend_Db_Expr $where
     * @return ManipleCore_Model_UserInterface|null
     */
    protected function _getUserBy($where)
    {
        $row = $this->_getUsersTable()->fetchRow($where);
        if ($row) {
            return $this->_setUserFromRow($this->createUser(), $row);
        }
        return null;
    }

    /**
     * @return ManipleCore_Model_DbTable_Users
     * @internal
     */
    protected function _getUsersTable()
    {
        return $this->_tableProvider->getTable('ManipleCore_Model_DbTable_Users');
    }

    /**
     * @param  ManipleCore_Model_UserInterface $user
     * @param  Zend_Db_Table_Row_Abstract $row
     * @return ManipleCore_Model_UserInterface
     * @internal
     */
    protected function _setUserFromRow(ManipleCore_Model_UserInterface $user, Zend_Db_Table_Row_Abstract $row)
    {
        // call reset() without warning on function result
        // $primaryKey = call_user_func('reset', $row->getPrimaryKey());
        // The above hack doesn't work on PHP 7.0 - it results in NULL value
        $primaryKey = $row->getPrimaryKey();
        $primaryKey = reset($primaryKey);

        $user->setFromArray($row->toArray());
        $user->setId($primaryKey);

        return $user;
    }
}
