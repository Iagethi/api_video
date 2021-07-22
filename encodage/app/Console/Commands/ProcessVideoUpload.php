<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSVideoFilters;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;

class ProcessVideoUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video-upload:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $media = FFMpeg::fromDisk('custom')->open('app/public/uploads/1623415024_2021-05-05 09-32-37.mkv');
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

        // FFMpeg::open('Laravelprjt.mp4')
        //     ->export()
        //     ->onProgress(function ($percentage) {
        //         echo "{$percentage}% transcoded";
        //     })
        //     ->toDisk('public')
        //     ->inFormat(new \FFMpeg\Format\Video\X264)
        //     ->addFilter(function (VideoFilters $filters) {
        //         $filters->resize(new \FFMpeg\Coordinate\Dimension(1280, 720));
        //     })
        //     ->save('Laravelprjt.mp4');
    }
}
