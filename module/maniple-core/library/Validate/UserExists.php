<?php

class ManipleCore_Validate_UserExists extends ManipleCore_Validate_User
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

        if (empty($this->_user)) {
            $this->_error(self::ERROR_USER_NOT_FOUND);
            return false;
        }

        return true;
    }
}
