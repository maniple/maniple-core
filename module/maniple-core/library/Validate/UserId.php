<?php

class ManipleCore_Validate_UserId extends ManipleCore_Validate_User
{
    const INVALID_USER_ID = 'invalidUserId';

    protected $_messageTemplates = array(
        self::INVALID_USER_ID => 'Invalid user ID',
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
