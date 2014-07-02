<?php

class ManipleCore_Model_User extends Maniple_Model_Model implements ManipleCore_Model_UserInterface
{
    protected $_userId;

    protected $_username;

    protected $_password;

    protected $_email;

    protected $_firstName;

    protected $_lastName;

    public function getId()
    {
        return $this->_userId;
    }
}
