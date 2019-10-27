<?php

class ManipleCore_Controller_Plugin_LogExtras extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Log
     */
    protected $_log;

    public function __construct(Zend_Log $log)
    {
        $this->_log = $log;
        $this->_log->setEventItem('referer', 'null');
        $this->_log->setEventItem('ip', '0.0.0.0');
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request instanceof Zend_Controller_Request_Http) {
            return;
        }

        $this->_log->setEventItem('referer', ($referer = $request->getHeader('referer')) ? $referer : 'null');
        $this->_log->setEventItem('ip', $request->getClientIp());
    }
}
