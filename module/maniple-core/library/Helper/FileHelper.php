<?php

/**
 * @uses        Zefram_File_MimeType
 * @author      xemlock
 * @version     2013-07-06
 */
class ManipleCore_Helper_FileHelper
{
    /**
     * @param  Zend_Controller_Request_Http $request
     * @param  Zend_Controller_Response_Http $response
     * @param  string $path
     * @param  array $options OPTIONAL
     * @return void
     */
    public function sendFile(Zend_Controller_Request_Http $request, Zend_Controller_Response_Http $response, $path, array $options = null) // {{{
    {
        if (!$response->canSendHeaders(true)) {
            return;
        }

        if (empty($path) || !is_file($path)) {
            throw new ManipleCore_Controller_NotFoundException;
        }

        if (!is_readable($path)) {
            throw new ManipleCore_Controller_NotAllowedException;
        }

        $headers = array();

        $last_modified = filemtime($path);

        if (isset($options['cache']) && $options['cache']) {
            $response->setHeader('Cache-Control', 'public, must-revalidate', true);
        }

        $if_modified_since = $request->getServer('HTTP_IF_MODIFIED_SINCE');
        if ($if_modified_since) {
            $if_modified_since = preg_replace('/;.*$/', '', $if_modified_since);
            $if_modified_since = strtotime($if_modified_since);

            if ($last_modified <= $if_modified_since) {
                $response->setHttpResponseCode(304); // Not Modified
                $response->sendResponse();
                exit;
            }
        }

        if (isset($options['type'])) {
            $type = $options['type'];
        } else {
            $type = 'application/octet-stream';
        }

        $response->setHeader('Content-Type', $type, true);
        $response->setHeader('Content-Transfer-Encoding', 'binary');
        $response->setHeader('Last-Modified', gmdate('r', $last_modified), true);

        if (isset($options['name'])) {
            $filename = $options['name'];
            $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"', true);
        }

        if (isset($options['etag'])) {
            $response->setHeader('ETag', $options['etag']);
        }

        $response->setHeader('Content-Length', filesize($path), true);

        $response->sendHeaders();

        while (@ob_end_clean());
        readfile($path);

        exit;
    } // }}}
}
