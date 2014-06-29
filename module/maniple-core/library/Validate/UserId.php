<?php

class ManipleCore_Validate_UserId extends Zend_Validate_Abstract
{
    const INVALID_USER_ID = 'invalidUserId';

    protected $_messageTemplates = array(
        self::INVALID_USER_ID => 'Invalid user ID',
    );

    protected $_messageVariables = array(
        'user' => '_user',
    );

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

    /**
     * @param  int $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (int) $value;

        $this->_value = $value;
        $this->_user = $this->getUserRepository()->getUser($value);

        if (empty($this->_user)) {
            $this->_error(self::INVALID_USER_ID);
            return false;
        }

        return true;
    }
}
