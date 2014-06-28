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
            $userClass = $this->_userClass;
            $user = new $userClass($row->toArray());
            return $user;
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

        $userClass = $this->_userClass;
        $users = array();

        if ($userIds) {
            $rows = $this->_getUsersTable()->fetchAll(array('user_id IN (?)' => $userIds));
            foreach ($rows as $row) {
                $user = new $userClass($row->toArray());
                $users[$user->getId()] = $user;
            }
        }

        return $users;
    }

    /**
     * @return ManipleCore_Model_DbTable_Users
     */
    protected function _getUsersTable()
    {
        return $this->_tableProvider->getTable('ManipleCore_Model_DbTable_Users');
    }
}
