@extends('template.master')
@section('title', 'Lecture Area')

@section('styles')
    <link href="{{asset('css/video-js.css')}}" rel="stylesheet" />
    <link href="{{asset('css/videojs-resolution-switcher.css')}}" rel="stylesheet" />
    <link href="{{asset('css/videojs-watermark.css')}}" rel="stylesheet" />
    <link href="{{asset('css/videojs-seek-buttons.css')}}" rel="stylesheet" />
    <link href="https://players.brightcove.net/videojs-thumbnails/videojs.thumbnails.css" rel="stylesheet" />
    <style>
        .video-js .vjs-big-play-button{font-size:6em !important;}
        .vjs-tech{object-fit: cover; }
        .video-wrapper{position:relative;}
        .vjs-watermark {position: absolute;display: inline;z-index: 1;}
        .vjs-watermark > img{width:100px;height:100px;}
    </style>
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Play a Video from S3 Bucket
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <video id="myvideo" width="100%" height="100%" class="video-js vjs-default-skin" poster="{{asset('img/sample/poster-sample.png')}}"></video>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        Upload Video to S3 Bucket
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">

                    Body
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('top_scripts')
    <script src="{{asset('js/video.js')}}"></script>
    <script src="{{asset('js/videojs-ie8.min.js')}}"></script>
    <script src="https://players.brightcove.net/videojs-thumbnails/videojs.thumbnails.js"></script>
    <script src="{{asset('js/videojs-resolution-switcher.js')}}"></script>
    <script src="{{asset('js/videojs-http-streaming.js')}}"></script>
    <script src="{{asset('js/videojs-watermark.js')}}"></script>
    <script src="{{asset('js/videojs.hotkeys.min.js')}}"></script>
    <script src="{{asset('js/videojs-seek-buttons.min.js')}}"></script>

    <script src="https://src.litix.io/videojs/2/videojs-mux.js"></script>
@endsection
@section('scripts')
    <script>
        var resoToggle = false;

        var thumbnails = {
            0: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000000.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            60: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000001.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            120: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000002.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            180: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000003.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            240: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000004.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            300: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000005.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            360: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000006.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            420: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000007.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            480: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000008.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            600: {
                src: "https://fastcpd.s3-ap-northeast-1.amazonaws.com/preview/LolMontagePreview.0000009.jpg",
                style: {
                    width: "200px",
                    left: "-55px",
                }
            },
            
        };
        var playerInitTime = Date.now();
        $(document).ready(function(){
            videojs('myvideo', {
                controls: true,
                aspectRatio: '16:9',
                fluid: true,
                playbackRates: [0.5, 1, 1.5, 2, 4],
                plugins: {
                    videoJsResolutionSwitcher: {
                        ui: true, 
                        default: 'low',
                        dynamicLabel: true,
                    },
                    mux: {
                        debug: false,
                        data: {
                            env_key: 'nbcob38iaskpm0282u5s2nn65', // required
                            player_name: 'CPD-VideoJS', // ex: 'My Main Player'
                            player_init_time: playerInitTime // ex: 1451606400000
                        }
                    },
                    watermark: {
                        file: "{{asset('img/system/icon-9.png')}}",
                        opacity: 0.3,
                        xpos: 100,
                        ypos: 0,
                    },
                }
            }, function(){
                var player = this;
                window.player = player
                player.updateSrc([
                {
                    src: 'https://fastcpd.s3-ap-northeast-1.amazonaws.com/convert/LolMontageOutput-360p.m3u8',
                    type: "application/x-mpegURL",
                    label: "360p",
                    placeholder: "360p",
                    res: 360,
                },{
                    src: 'https://fastcpd.s3-ap-northeast-1.amazonaws.com/convert/LolMontageOutput-480p.m3u8',
                    type: "application/x-mpegURL",
                    label: "480p",
                    placeholder: "480p",
                    res: 480,
                },{
                    src: 'https://fastcpd.s3-ap-northeast-1.amazonaws.com/convert/LolMontageOutput-720p.m3u8',
                    type: "application/x-mpegURL",
                    label: "720p",
                    placeholder: "720p",
                    res: 720,
                }
                ])
                player.seekButtons({
                    forward: 0,
                    back: 10
                })
                this.hotkeys({
                    volumeStep: 0.1,
                    seekStep: 5,
                    enableModifiersForNumbers: false
                })
                player.controlBar.progressControl.disable();
                player.on('resolutionchange', function(){
                    // The resolution label was not updating or showing
                    // Create a customized reso label on evert change of resolution
                    $("button.vjs-resolution-button > span.vjs-icon-placeholder").html(player.currentResolutionState.label);
                })
                player.thumbnails(thumbnails);
            });

            // The resolution label was not updating or showing
            // Create a customized reso label on first play toggle
            $("button.vjs-big-play-button").click(function(){
                if(!resoToggle){
                    $("button.vjs-resolution-button > span.vjs-icon-placeholder").html("360p");
                    resoToggle=true;
                }
            });
        });
    </script>
@endsection
