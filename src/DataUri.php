<?php

namespace Enl\Flysystem\Cloudinary;

/**
 * Class DataUri
 * Creates DATA-URI formatted string from file content.
 *
 * @author Alex Panshin <deadyaga@gmail.com>
 *
 * @codeCoverageIgnore
 */
class DataUri
{
    /** @var string */
    private $content;

    /** @var \finfo */
    private $finfo;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return \finfo
     */
    private function getFileInfo()
    {
        if (!$this->finfo) {
            $this->finfo = new \finfo(FILEINFO_MIME_TYPE);
        }

        return $this->finfo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'data:%s;base64,%s',
            $this->getFileInfo()->buffer($this->content),
            base64_encode($this->content)
        );
    }
}
