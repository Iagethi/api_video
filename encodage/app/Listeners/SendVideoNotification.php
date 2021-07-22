<?php

namespace App\Listeners;

use App\Events\VideoProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class SendVideoNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VideoProcessed  $event
     * @return void
     */
    public function handle(VideoProcessed $event)
    {
        $media = FFMpeg::open($event->video->source);
        $dimension = $media->getVideoStream()->getDimensions()->getHeight();
        $formatVideoHeight = [1080, 720, 480, 360, 240];
        $formatVideoWidth = [1920, 1280, 854, 640, 426];

        $whereToStart = 0;
        for ($i = 0; $i <= 4; $i++) {
            if ($formatVideoHeight[$i] > $dimension) {
                $whereToStart++;
            }
        }

        for ($i = $whereToStart; $i <= 4; $i++) {
            $media = $media
                ->export()
                ->toDisk('public')
                ->inFormat(new \FFMpeg\Format\Video\X264)
                ->resize($formatVideoWidth[$i], $formatVideoHeight[$i])
                ->save('Test' . $formatVideoHeight[$i] . '.mp4');
        }
    }
}
