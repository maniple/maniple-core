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
            throw new Maniple_Controller_NotFoundException('File not found');
        }

        if (!is_readable($path)) {
            throw new Maniple_Controller_NotAllowedException('Unable to read file contents');
        }

        $last_modified = filemtime($path);

        if (isset($options['cache']) && $options['cache']) {
            $response->setHeader('Cache-Control', 'public, must-revalidate', true);
        }

        if (isset($options['type'])) {
            $type = $options['type'];
        } else {
            $type = 'application/octet-stream';
        }

        $response->setHeader('Content-Type', $type, true);
        $response->setHeader('Content-Transfer-Encoding', 'binary', true);

        if ($this->_acceleratorHeader === 'X-Sendfile') {
            // absolute path required
            $response->setHeader('X-Sendfile', realpath($path), true);
            $response->sendHeaders();
            echo 'If you see this message X-SENDFILE is not enabled';
            return;
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

        // header('Accept-Ranges: bytes');

        $response->setHeader('Last-Modified', gmdate('r', $last_modified), true);

        if (isset($options['name'])) {
            $filename = $options['name'];
            $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"', true);
        }

        $response->setHeader('Content-Length', filesize($path), true);

        if (isset($options['etag'])) {
            $response->setHeader('ETag', $options['etag'], true);
        }

        /*
        $size = filesize($path);
        $begin = 0;
        $end = $size;

        $range = $request->getServer('HTTP_RANGE');
        // Multiple ranges!
        if (isset($range)
            && preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $range, $match)
        ) {
            $begin = intval($match[0]);
            if (isset($matches[1])) {
                $end = intval($match[1]);
            }
            // header("Content-Range: bytes $range-$range_end/$size");
        }

        if ($begin > 0 || $end < $size) {
            // $response->setHttpResponseCode(206);
        } else {
            $response->setHttpResponseCode(200);
        }
        */

        $response->sendHeaders();

        while (@ob_end_clean());
        $this->readfile($path);

        exit;
    } // }}}

    // Read a file and display its content chunk by chunk
    public function readfile($filename)
    {
        $handle = fopen($filename, "rb");
        if ($handle === false) {
            return false;
        }
        $readBytes = 0;
        while (!feof($handle) && !connection_aborted()) {
            $buffer = fread($handle, $this->_bytes);
            echo $buffer;
            flush();
            $readBytes += strlen($buffer);

            // default throttle 400kbps
            usleep($this->_sec * 1E6);
        }
        fclose($handle);
        return $readBytes;
    }

    protected $_acceleratorHeader;

    public function setAcceleratorHeader($header)
    {
        $this->_acceleratorHeader = $header;
    }

    public function setThrottle($sec, $bytes)
    {
        $this->_sec = (float) $sec;
        $this->_bytes = (int) $bytes;
    }

    /**
     * Delay between consecutive chunks
     * @var int
     */
    protected $_sec = 0.0025;

    /**
     * Chunk size in bytes
     * @var int
     */
    protected $_bytes = 40960;
}
