<?php

/**
 * @property Zend_Controller_Request_Http $_request
 * @property Zend_Controller_Response_Http $_response
 */
class ManipleCore_ErrorController extends Maniple_Controller_Action
{
    /**
     * @Inject('Log')
     * @var Zend_Log|null
     */
    protected $_log;

    public function errorAction()
    {
        // TODO: settings: disableLayout, authRoute
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(false);

        $errors = $this->getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        $exception = $errors->exception;

        if (
            $exception instanceof Maniple_Controller_Exception_AuthenticationRequired &&
            ($continueUrl = $exception->getContinueUrl())
        ) {
            // TODO: Configuration for Auth route
            $this->_helper->redirector->gotoUrlAndExit(
                $this->view->url('user.auth.login') . '?continue=' . urlencode($continueUrl)
            );
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->_response->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $message = 'Page not found';
                break;

            default:
                if ($exception instanceof Maniple_Controller_Exception) {
                    $this->_response->setHttpResponseCode($exception->getCode());
                    $priority = Zend_Log::NOTICE;
                    $message = $exception->getMessage();
                } else {
                    $this->_response->setHttpResponseCode(500);
                    $priority = Zend_Log::CRIT;
                    $message = 'Application error';
                }
                break;
        }


        if ($this->_log) {
            $this->_log->log($errors->exception->getMessage(), $priority, $exception);
            $this->_log->log('Request Parameters', $priority, $this->_maskPasswords($errors->request->getParams()));
        }

        if ($this->_request->isXmlHttpRequest()) {
            $response = array('message' => $message);

            if ($this->getInvokeArg('displayExceptions')) {
                $response['exception'] = array(
                    'message' => $exception->getMessage(),
                    'code'    => $exception->getCode(),
                    'type'    => get_class($exception),
                    'trace'   => $exception->getTrace(),
                );
            }

            $this->_helper->json($response);
            return;
        }

        $this->view->message = $message;
        $this->view->request = $errors->request;

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions')) {
            $this->view->exception = $errors->exception;
        }

        if ($this->_response->getHttpResponseCode() === 404) {
            $this->_helper->viewRenderer->setRender('error-404');
        }
    }

    protected function _maskPasswords(array $params)
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $params[$key] = $this->_maskPasswords($value);
            } elseif (is_string($value) && stripos($key, 'password') !== false) {
                $params[$key] = str_repeat('*', strlen($value));
            }
        }
        return $params;
    }
}
