<?php

class ManipleCore_View_Helper_FullName extends Zend_View_Helper_Abstract
{
    /**
     * @var array
     */
    protected $_options = array(
        'escape'          => true,
        'reverse'         => false,
        'attribPrefix'    => '',
        'attribSuffix'    => '',
        'firstNameAttrib' => 'firstName',
        'lastNameAttrib'  => 'lastName',
        'midNameAttrib'   => 'midName',
    );

    /** 
     * @param  array $options
     * @return ManipleCore_View_Helper_FullName
     */
    public function setOptions(array $options)
    {
        $this->_options = array_merge(
            $this->_options,
            array_intersect_key(
                array_keys($this->_options),
                $options
            )
        );
        return $this;
    }

    /**
     * @param  array|object $user
     * @param  array $options OPTIONAL
     * @return string
     */
    public function fullName($user, array $options = null)
    {
        $options = array_merge($this->_options, (array) $options);

        $prefix = $options['attribPrefix'];
        $suffix = $options['attribSuffix'];

        $firstNameAttrib = $prefix . $options['firstNameAttrib'] . $suffix;
        $lastNameAttrib  = $prefix . $options['lastNameAttrib']  . $suffix;
        $midNameAttrib   = $prefix . $options['midNameAttrib']   . $suffix;

        $firstName = trim($this->_getAttrib($user, $firstNameAttrib));
        $lastName  = trim($this->_getAttrib($user, $lastNameAttrib));
        $midName   = trim($this->_getAttrib($user, $midNameAttrib));

        if ($options['reverse']) {
            // eastern name order
            $fullName = $lastName
                      . (strlen($midName)   ? ' ' . $midName   : '')
                      . (strlen($firstName) ? ' ' . $firstName : '');
        } else {
            $fullName = $firstName
                      . (strlen($midName)  ? ' ' . $midName  : '')
                      . (strlen($lastName) ? ' ' . $lastName : '');
        }

        if ($options['escape']) {
            return $this->view->escape($fullName);
        }

        return $fullName;
    }

    /**
     * @param  mixed $container
     * @param  string $attrib
     * @reutrn mixed
     */
    protected function _getAttrib($container, $attrib)
    {
        $attrib = (string) $attrib;

        if ((is_array($container) || $container instanceof ArrayAccess) &&
            isset($container[$attrib])
        ) {
            return $container[$attrib];
        }

        if (is_object($container) && isset($container->{$attrib})) {
            return $container->{$attrib};
        }

        return null;
    }
}
