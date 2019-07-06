<?php

class ManipleCore_Settings_SettingsManager
{
    const className = __CLASS__;

    /**
     * @var ManipleCore_Settings_Adapter_Interface
     */
    protected $_adapter;

    /**
     * @var Zend_EventManager_EventManager
     */
    protected $_events;

    /**
     * @var Zend_Log
     */
    protected $_log;

    /**
     * @var array[]
     */
    protected $_settings = array();

    public function __construct(
        ManipleCore_Settings_Adapter_Interface $adapter,
        Zend_EventManager_SharedEventManager $sharedEventManager,
        Zend_Log $log = null
    ) {
        $this->_adapter = $adapter;

        $this->_events = new Zend_EventManager_EventManager();
        $this->_events->setIdentifiers(array(
            'Maniple.SettingsManager',
            get_class($this),
        ));
        $this->_events->setSharedCollections($sharedEventManager);

        // Notify listeners that this service is ready for initialization
        $this->_events->trigger('init', $this);
    }

    /**
     * Registers setting in the manager.
     *
     * Type can be given as an array, in such case options param is ignored, and type
     * is determined by the value under 'type' key.
     *
     * Options:
     *   - string 'type' - only if type param is given as array
     *   - mixed 'default'
     *   - string 'label'
     *   - string 'description'
     *
     * @param string $key
     * @param string|array $type
     * @param array $options OPTIONAL
     * @return $this
     */
    public function register($key, $type = null, array $options = array())
    {
        $key = (string) $key;

        if (isset($this->_settings[$key])) {
            throw new ManipleCore_Settings_Exception_InvalidArgumentException(sprintf(
                'Setting \'%s\' is already registered',
                $key
            ));
        }

        if (is_array($type)) {
            $options = $type;
            $type = isset($options['type']) ? $options['type'] : null;
        }

        if ($type === null) {
            $type = 'string';
        }

        if (!is_string($type)) {
            throw new ManipleCore_Settings_Exception_InvalidArgumentException(
                'Setting type must be a string indicating type'
            );
        }

        $type = $this->_checkType($type);

        $default = isset($options['default'])
            ? $this->_coerceValue($type, $options['default'])
            : null;

        $label = isset($options['label']) ? (string) $options['label'] : null;
        $description = isset($options['description']) ? (string) $options['description'] : null;

        $this->_settings[$key] = compact('type', 'default', 'label', 'description');

        return $this;
    }

    /**
     * Retrieve setting value at the given key.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $key = (string) $key;
        $value = $this->_adapter->get(
            $key,
            isset($this->_settings[$key]) ? $this->_settings[$key]['default'] : null
        );

        return $value;
    }

    /**
     * Sets setting value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws ManipleCore_Settings_Exception_InvalidArgumentException If setting key is not registered
     */
    public function set($key, $value)
    {
        $key = (string) $key;

        if (!isset($this->_settings[$key])) {
            throw new ManipleCore_Settings_Exception_InvalidArgumentException(sprintf(
                'Setting \'%s\' is not registered',
                $key
            ));
        }

        $value = $this->_coerceValue($key, $value);
        $this->_adapter->set($key, $value);

        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove($key)
    {
        $this->_adapter->remove((string) $key);
        return $this;
    }

    /**
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    protected function _coerceValue($type, $value)
    {
        if ($value === null) {
            return null;
        }

        settype($value, $type);
        return $value;
    }

    /**
     * @param string $type
     * @return string
     * @throws ManipleCore_Settings_Exception_InvalidArgumentException If unrecognized type provided
     */
    protected function _checkType($type)
    {
        $type = (string) $type;

        switch ($type) {
            case 'int':
            case 'integer':
                return 'int';

            case 'float':
            case 'double':
                return 'float';

            case 'bool':
            case 'boolean':
                return 'bool';

            case 'string':
                return 'string';

            case 'array':
                return 'array';

            default:
                throw new ManipleCore_Settings_Exception_InvalidArgumentException(sprintf(
                    'Unsupported setting type \'%s\'',
                    $type
                ));
        }
    }
}
