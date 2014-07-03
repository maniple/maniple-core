<?php

class ManipleCore_Validate_UserNotExists extends ManipleCore_Validate_User
{
    /**
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException
     */
    public function isValid($value)
    {
        $this->_value = $value;
        $this->_user = $this->_getUserByValue($value);

        if ($this->_user) {
            $this->_error(self::USER_EXISTS);
            return false;
        }

        return true;
    }
}
