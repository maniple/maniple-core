<?php

abstract class ManipleCore_Validate_User extends Zend_Validate_Abstract
{
    const MATCH_ID                = 'id';
    const MATCH_EMAIL             = 'email';
    const MATCH_USERNAME          = 'username';
    const MATCH_USERNAME_OR_EMAIL = 'usernameOrEmail';

    const ERROR_USER_NOT_FOUND    = 'errorUserNotFound';
    const ERROR_USER_FOUND        = 'errorUserFound';

    /**
     * @var ManipleCore_Model_UserRepositoryInterface
     */
    protected $_userRepository;

    /**
     * @var ManipleCore_Model_UserInterface
     */
    protected $_user;

    /**
     * @var string
     */
    protected $_matchBy = self::MATCH_ID;

    protected $_messageTemplates = array(
        self::ERROR_USER_NOT_FOUND => 'No matching user was found',
        self::ERROR_USER_FOUND     => 'A matching user was found',
    );

    protected $_messageVariables = array(
        'user' => '_user',
    );

    /**
     * Constructor.
     *
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
     * @param  string $matchBy
     * @return ManipleCore_Validate_UserExists
     * @throws InvalidArgumentException
     */
    public function setMatchBy($matchBy)
    {
        $matchBy = (string) $matchBy;

        switch ($matchBy) {
            case self::MATCH_ID:
            case self::MATCH_EMAIL:
            case self::MATCH_USERNAME:
            case self::MATCH_USERNAME_OR_EMAIL:
                $this->_matchBy = $matchBy;
                break;

            default:
                throw new InvalidArgumentException(sprintf(
                    "Unsupported matchBy option value: '%s'", $matchBy
                ));
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMatchBy()
    {
        return $this->_matchBy;
    }

    /**
     * Retrieves user from repository matched by given value interpreted
     * according to current matchBy setting.
     *
     * @param  mixed $value
     * @return ManipleCore_Model_UserInterface
     */
    protected function _getUserByValue($value)
    {
	$user = null;

        switch ($this->_matchBy) {
            case self::MATCH_ID:
                $user = $this->getUserRepository()->getUser($value);
                break;

            case self::MATCH_USERNAME_OR_EMAIL:
                $user = $this->getUserRepository()->getUserByUsernameOrEmail($value);
                break;

            default:
                throw new RuntimeException(sprintf(
                    "Unsupported matchBy option value: '%s'", $this->_matchBy
                ));
	}

	return $user;
    }
}