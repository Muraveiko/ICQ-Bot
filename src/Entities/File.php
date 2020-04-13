<?php
/**
 * This file is part of the IcqBot package.
 *
 * (c) Oleg Muraveyko aka Antson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Antson\IcqBot\Entities;

/**
 * Загруженный документ
 *
 * @package Antson\IcqBot\Entities
 *
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

    /**
     * @var string|null one of the constants File::TYPE_
     */
    protected $type;

    /**
     * @var string|null Подпись к файлу.
     */
    protected $caption;

    /**
     * Перекрыть конструктор потребовалось из-за отдельных вариантов VOICE и IMAGE
     * @param $data
     * @param string|null $type
     * @param string|null $caption
     */
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