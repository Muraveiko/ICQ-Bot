<?php


namespace Antson\IcqBot\Entities;

/**
 * @method string get_type()
 * @method string get_fileId()
 * @method string get_caption()
 */
class File extends Entity
{
    const TYPE_IMAGE = "image";
    const TYPE_VIDEO = "video";
    const TYPE_AUDIO = "audio";
    const TYPE_VOICE = "voice";

    protected $type;
//    protected $fileId;
    protected $caption;

    public function __construct($data, $type = null, $caption = null)
    {
        parent::__construct($data);
        if (!is_null($type)) {
            $this->type = $type;
        }
        if (!is_null($caption)) {
            $this->caption = $caption;
        }
    }
}