<?php

namespace controller;

use core\Controller;

use Scratchy\component\PowerPoint;
use Scratchy\component\PowerPointSlide;
use Scratchy\component\PowerPointSlides;
use Scratchy\component\PowerPointVideo;

/** @noinspection PhpUnused */
class CeremonyController extends Controller
{
    public function __construct()
    {
        $this->webPageTemplate = PowerPoint::class;
        $this->title = "Awards ceremony";
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function index(): PowerPointSlides
    {
        $welcomeVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770172268/awards2026/intro-loop-15_uqzvdi.mp4");
        $maddyWalkUpVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770172231/awards2026/maddy-wirth-intro_uhjvjf.mp4");
        $larissaWalkUpVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770172230/awards2026/larissa-jacobs-intro_z967rf.mp4");
        $taylorWalkUpVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770172231/awards2026/taylor-strader-intro_pyqcww.mp4");
        $johnnyLindemanWalkUpVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770172229/awards2026/johnny-lindeman-intro_kntp51.mp4");
        $nicolaWrightWalkUpVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770175585/awards2026/nicola-wright-intro_wv8ky2.mp4");
        $yleLusigneaWalkUpVideo = new PowerPointVideo("https://res.cloudinary.com/tritium/video/upload/v1770176759/awards2026/kyle-lusignea-intro_u6zggs.mp4");

        $activeSlide = $this->data->get('slide') ?? 0;

        $PowerPointSlides = new PowerPointSlides(
            new PowerPointSlide(
                contentList: [
                    $welcomeVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: 'Welcome to the 2026 John\'s birthday party awards ceremony.'
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
            new PowerPointSlide(
                contentList: [
                    $johnnyLindemanWalkUpVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
            new PowerPointSlide(
                contentList: [
                    $maddyWalkUpVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
            new PowerPointSlide(
                contentList: [
                    $larissaWalkUpVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
            new PowerPointSlide(
                contentList: [
                    $nicolaWrightWalkUpVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
            new PowerPointSlide(
                contentList: [
                    $taylorWalkUpVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
            new PowerPointSlide(
                contentList: [
                    $yleLusigneaWalkUpVideo,
                ],
                fullScreenContent: true,
            ),
            new PowerPointSlide(
                contentList: [

                ],
                title: ''
            ),
        );

        $PowerPointSlides->currentSlide = $activeSlide;
        return $PowerPointSlides;
    }
}