<?php

class ManipleCore_Validate_UsernameOrEmail extends ManipleCore_Validate_User
{
    const INVALID_USERNAME_OR_EMAIL = 'invalidUsernameOrEmail';

    protected $_messageTemplates = array(
        self::INVALID_USERNAME_OR_EMAIL => 'Invalid username or email address',
    );

    protected $_messageVariables = array(
        'user' => '_user',
    );

    /**
     * @param  int $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;

        $this->_value = $value;
        $this->_user = $this->getUserRepository()->getUserByUsernameOrEmail($value);

        if (empty($this->_user)) {
            $this->_error(self::INVALID_USERNAME_OR_EMAIL);
            return false;
        }

        return true;
    }
}
