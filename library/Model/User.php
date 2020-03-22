<?php

/**
 * @deprecated
 */
class ManipleCore_Model_User extends Maniple_Model_Model implements ManipleCore_Model_UserInterface
{
    /**
     * @var mixed
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_username;

    /**
     * @var string
     */
    protected $_password;

    /**
     * @var string
     */
    protected $_email;

    /**
     * @var mixed
     */
    protected $_createdAt;

    /**
     * @param  mixed $id
     * @return ManipleCore_Model_User
     */
    public function setId($id)
    {
        if (ctype_digit($id)) {
            $numericId = (float) $id;
            if ((string) $numericId === (string) $id) {
                $id = $numericId;
            }
        }
        $this->_id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    public function getCreatedAt()
    {
        return $this->_createdAt;
    }
}
