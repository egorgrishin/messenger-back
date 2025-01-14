<?php

namespace App\Services\File\Classes\Saver;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

class Video extends SaverHandler
{
    protected const TYPE               = 'videos';
    private const   BIT_RATE_FIELD     = 'bit_rate';
    private const   AUDIO_CODEC        = 'aac';
    private const   VIDEO_CODEC        = 'libx264';
    private const   MAX_VIDEO_BIT_RATE = 3000;
    private const   AUDIO_BIT_RATE     = 128;
    private const   ADDITIONAL_PARAMS  = [
        '-movflags', '+faststart',
        '-preset', 'ultrafast',
    ];

    /**
     * Возвращает расширение, с которым необходимо сохранить файл
     */
    protected function getTargetExtension(): string
    {
        return 'mp4';
    }

    /**
     * Сохраняет изображение в хранилище
     */
    public function save(): string
    {
        $this->file->storeAs($this->path, $this->fileName, ['disk' => 'files']);
        return $this->fileName;

//        $ffmpeg = FFMpeg::create();
//
//        $name = 'input.mp4';
//        $out = 'output.mp4';
//
//        $video = $ffmpeg->open(__DIR__ . "/$name");
//        $video->frame(TimeCode::fromSeconds(0))
//            ->save(__DIR__ . '/preview.jpg');
//
//        $videoBitRate = intdiv((int) $video->getFormat()->get(self::BIT_RATE_FIELD), 1000);
//        $maxBitRate = min($videoBitRate, self::MAX_VIDEO_BIT_RATE);
//
//        $format = (new X264(self::AUDIO_CODEC, self::VIDEO_CODEC))
//            ->setAudioKiloBitrate(self::AUDIO_BIT_RATE)
//            ->setKiloBitrate($maxBitRate)
//            ->setAdditionalParameters(self::ADDITIONAL_PARAMS);
//
//        $video->save($format, __DIR__ . "/$out");
    }
}
