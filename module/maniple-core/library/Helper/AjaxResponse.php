<?php

class ManipleCore_Helper_AjaxResponse
{
    /**
     * @var array
     */
    protected $_response = array();

    protected $_httpCode = 200;

    protected $_suppressExit = false;

    /**
     * @param  int $httpCode
     * @return ManipleCore_Helper_AjaxResponse
     */
    public function setHttpCode($httpCode)
    {
        $httpCode = (int) $httpCode;
        if ($httpCode < 100 || 700 <= $httpCode) {
            throw new InvalidArgumentException("Invalid HTTP response code ($httpCode)");
        }
        $this->_httpCode = (int) $httpCode;
        return $this;
    }

    /** 
     * @return int
     */
    public function getHttpCode()
    {
        return $this->_httpCode;
    }

    /**
     * @param  string $message
     * @param  mixed $error
     * @return ManipleCore_Helper_AjaxResponse
     */
    public function setError($message, $error = true, $httpCode = 200)
    {
        if (!$error) {
            throw new InvalidArgumentException("error parameter must evaluate to a truthy value");
        }

        $this->setHttpCode($httpCode);
        $this->_response['error'] = $error;
        $this->_response['message'] = (string) $message;

        return $this;
    }

    /**
     * @param  string $message OPTIONAL
     * @return ManipleCore_Helper_AjaxResponse
     */
    public function setSuccess($message = null)
    {
        $this->setHttpCode(200);
        unset($this->_response['error']);

        if (isset($message)) {
            $this->_response['message'] = true;
        } else {
            unset($this->_response['message']);
        }

        return $this;
    }

    /**
     * @param  string $url OPTIONAL
     * @return ManipleCore_Helper_AjaxResponse
     */
    public function setRedirect($url)
    {
        $this->setHttpCode(200);
        $this->_response['redirect'] = (string) $url;
        return $this;
    }

    public function clearRedirect()
    {
        unset($this->_response['redirect']);
        return $this;
    }

    /**
     * @param  mixed $data OPTIONAL
     * @return ManipleCore_Helper_AjaxResponse
     */
    public function setData($data = null)
    {
        if (isset($data)) {
            $this->_response['data'] = $data;
        } else {
            unset($this->_response['data']);
        }
        return $this;
    }

    public function __toString()
    {
        return Zefram_Json::encode($this->_response);
    }

    public function getContentType()
    {
        return 'application/json';
    }

    public function send(Zend_Controller_Response_Http $response)
    {
        $response->setHttpResponseCode($this->getHttpCode());
        $response->setHeader('Content-Type', $this->getContentType());
        $response->setBody((string) $this);

        $response->sendHeaders();
        $response->sendResponse();

        if (!$this->_suppressExit) {
            if (class_exists('Zend_Session', false) && Zend_Session::isStarted()) {
                Zend_Session::writeClose();
            } elseif (isset($_SESSION)) {
                session_write_close();
            }
            exit;
        }
    }
}
