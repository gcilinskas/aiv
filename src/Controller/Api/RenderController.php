<?php

namespace App\Controller\Api;

use App\Kernel;
use App\Response\ArrayResponse;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Media\Gif;
use FFMpeg\Media\Video;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
// Use the FFMpeg tool
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

/**
 * Class UserController
 * @Route("/render")
 */
class RenderController extends BaseController
{
    /**
     * @Route("/frames", methods={"POST"})
     * @return Response
     */
    public function createFrames(ArrayResponse $response, KernelInterface $kernel)
    {
        $path = __DIR__  . '/../../frames/';
        if (isset($_POST['data']) && isset($_POST['i']) && is_numeric($_POST['i'])) {
            // split the data URL at the comma
            $data = explode(',', $_POST['data']);
            // decode the base64 into binary data
            $data = base64_decode(trim($data[1]));

            // create the numbered image file
            $filename = sprintf('%s%08d.png', $path, $_POST['i']);
            file_put_contents($filename, $data);
        }

        return $this->handleResponseView($response->setEntities([]));
    }

    /**
     * @Route("/video", methods={"POST"})
     * @return Response
     */
    public function createVideo(ArrayResponse $response, KernelInterface $kernel)
    {
        $path = __DIR__  . '/../../frames/';

        $process = new Process('ffmpeg -framerate 24 -i ../src/frames/%08d.png output.mp4');

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $this->handleResponseView($response->setEntities([]));
    }
}
