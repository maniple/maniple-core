<?php

class ManipleCore_Model_User extends Maniple_Model_Model implements ManipleCore_Model_UserInterface
{
    /**
     * @var mixed
     */
    protected $_userId;

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
     * @var string
     * @deprecated
     */
    protected $_firstName;

    /**
     * @var string
     * @deprecated
     */
    protected $_lastName;

    public function getId()
    {
        return $this->_userId;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getCreatedAt()
    {
        return $this->_createdAt;
    }
}
