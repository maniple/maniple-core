<?php

abstract class ManipleCore_Validate_User extends Zend_Validate_Abstract
{
    /**
     * @var ManipleCore_Model_UserRepositoryInterface
     */
    protected $_userRepository;

    /**
     * @var ManipleCore_Model_UserInterface
     */
    protected $_user;

    /**
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        if ($options) {
            foreach ($options as $key => $value) {
                $method = 'set' . $key;
                if (method_exists($this, $method)) {
                    $this->{$method}($value);
                }
            }
        }
    }

    /**
     * @param  ManipleCore_Model_UserRepositoryInterface $userRepository
     * @return Core_Validate_UserExists
     */
    public function setUserRepository(ManipleCore_Model_UserRepositoryInterface $userRepository)
    {
        $this->_userRepository = $userRepository;
        return $this;
    }

    /**
     * @return ManipleCore_Model_UserRepositoryInterface
     * @throws Exception
     */
    public function getUserRepository()
    {
        if (empty($this->_userRepository)) {
            throw new Exception('User repository is not configured');
        }
        return $this->_userRepository;
    }
}
