<?php

namespace App\Services\File\Classes;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

class VideoCompressor
{
    private const BIT_RATE_FIELD     = 'bit_rate';
    private const AUDIO_CODEC        = 'aac';
    private const VIDEO_CODEC        = 'libx264';
    private const MAX_VIDEO_BIT_RATE = 3000;
    private const AUDIO_BIT_RATE     = 128;
    private const ADDITIONAL_PARAMS  = [
        '-movflags', '+faststart',
        '-preset', 'ultrafast',
    ];

    public static function compress(): void
    {
        $ffmpeg = FFMpeg::create();

        $name = 'input.mp4';
        $out = 'output.mp4';

        $video = $ffmpeg->open(__DIR__ . "/$name");

        $videoBitRate = intdiv((int) $video->getFormat()->get(self::BIT_RATE_FIELD), 1000);
        $maxBitRate = min($videoBitRate, self::MAX_VIDEO_BIT_RATE);

        $format = (new X264(self::AUDIO_CODEC, self::VIDEO_CODEC))
            ->setAudioKiloBitrate(self::AUDIO_BIT_RATE)
            ->setKiloBitrate($maxBitRate)
            ->setAdditionalParameters(self::ADDITIONAL_PARAMS);

        $video->save($format, __DIR__ . "/$out");
    }
}