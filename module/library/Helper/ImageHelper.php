<?php

/**
 * @version 2014-06-22 / 2013-01-29
 */
class ManipleCore_Helper_ImageHelper
{
    /**
     * @var string
     */
    protected $_storageDir;

    /**
     * @var string[]
     */
    protected $_allowedDimensions;

    /**
     * @param  string $path
     * @return ManipleCore_Helper_ImageHelper
     * @throws Exception
     */
    public function setStorageDir($path) // {{{
    {
        $path = rtrim($path, '\\/');
        if (!is_dir($path) || !is_writable($path) || !is_readable($path)) {
            throw new Exception(sprintf(
                'Invalid storage dir provided (%s)', $path
            ));
        }
        $this->_storageDir = $path . '/';
        return $this;
    } // }}}

    /**
     * @return string
     * @throws Exception
     */
    public function getStorageDir() // {{{
    {
        if (empty($this->_storageDir)) {
            $tempDir = Zefram_Os::getTempDir();
            if (!$tempDir) {
                throw new Exception('Unable to locate temporary directory');
            }

            $storageDir = $tempDir . '/' . __CLASS__;
            if (!is_dir($storageDir)) {
                mkdir($storageDir);
            }

            $this->setStorageDir($storageDir);
        }
        return $this->_storageDir;
    } // }}}

    /**
     * Set allowed dimensions when resizing an image.
     *
     * @param  array|string $dimensions
     * @return ManipleCore_Helper_ImageHelper
     */
    public function setAllowedDimensions($dimensions) // {{{
    {
        $allowedDimensions = array();
        foreach ((array) $dimensions as $value) {
            if (!preg_match('/^(?P<width>\d+)x(?P<height>\d+)$/i', $value, $match)) {
                throw new InvalidArgumentException(sprintf('Invalid dimension spec: %s', $value));
            }
            $allowedDimensions[] = $match['width'] . 'x' . $match['height'];
        }
        $this->_allowedDimensions = $allowedDimensions;
        return $this;
    } // }}}

    /**
     * @return string[]
     */
    public function getAllowedDimensions() // {{{
    {
        return (array) $this->_allowedDimensions;
    } // }}}

    /**
     * @param  string $filename
     * @param  array $options OPTIONAL
     * @return string
     * @throws Zefram_Image_Exception
     * @throws Maniple_Controller_NotFoundException
     */
    public function getImagePath($filename, array $options = null) // {{{
    {
        if (strlen($filename) && is_file($filename) && is_readable($filename)) {
            $filename = realpath($filename);

            $width = $height = 0;

            if (isset($options['width'])) {
                $width = max(0, (int) $options['width']);
            }
            if (isset($options['height'])) {
                $height = max(0, (int) $options['height']);
            }

            // if width and height were not given return original image
            if ($width + $height == 0) {
                return $filename;
            }

            // if invalid dimensions return original image
            if ($this->_allowedDimensions && !in_array("{$width}x{$height}", $this->_allowedDimensions, true)) {
                return $filename;
            }

            // check if image exists in cache and it hasn't not expired

            $scale = isset($options['scale']) && $options['scale'];
            $crop = $scale && isset($options['crop']) && $options['crop'];

            $info = Zefram_Image::getInfo($filename);
            switch ($info[Zefram_Image::INFO_EXTENSION]) {
                case 'jpg':
                    $ext = 'jpg';
                    break;

                default:
                    $ext = 'png';
                    break;
            }

            $width = min($width, $info[Zefram_Image::INFO_WIDTH]);
            $height = min($height, $info[Zefram_Image::INFO_HEIGHT]);

            $thumb = $this->getStorageDir() . sprintf("%s.%dx%d.%d.%s",
                        md5($filename), $width, $height, $scale + $crop, $ext);

            if (is_file($thumb) && filemtime($filename) < filemtime($thumb)) {
                return $thumb;
            }

            // image was not found or expired
            $image = new Zefram_Image($filename);

            if ($scale) {
                $image->scale($width, $height, $crop)->save($thumb);
            } else {
                $image->resize($width, $height)->save($thumb);
            }

            return $thumb;
        }

        throw new Maniple_Controller_NotFoundException('Image file not found');
    } // }}}

    /**
     * @param string $dimensions
     * @param array $allowed OPTIONAL
     * @return array
     */
    public function parseDimensions($dimensions, array $allowed = null) // {{{
    {
        // cast all allowed image dimensions to string, beware:
        // 65 == "65x20", (string) 65 != "65x20"

        $dimensions = (string) $dimensions;

        if (!preg_match('/^\s*(?P<width>\d+)x(?P<height>\d+)\s*$/', $dimensions, $match)) {
            return false;
        }

        if (null !== $allowed) {
            $allowed = array_map('trim', (array) $allowed);
            if (!in_array($dimensions, $allowed, true)) {
                return false;
            }
        }

        // WIDTHxHEIGHT
        return array(
            'width'  => intval($match['width']),
            'height' => intval($match['height']),
        );
    } // }}}

    public function pixel() // {{{
    {
        // Smallest possible transparent GIF image, based on:
        // http://probablyprogramming.com/2009/03/15/the-tiniest-gif-ever
        header('Content-Type: image/gif');
        echo base64_decode('R0lGODlhAQABAIABAP///wAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
        exit;
    } // }}}
}
