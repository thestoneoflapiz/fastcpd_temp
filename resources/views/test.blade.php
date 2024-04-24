<!DOCTYPE html>
<html lang="en">
	<head>
        <script data-cfasync="false">
            var swarmoptions = {
                swarmcdnkey: "5eef6b5e-a213-4c17-a6b4-f145d00b90b7",
                iframeReplacement: "iframe",
                autoreplace: {
                    youtube: false,
                    videotag: true
                },
                theme: {
                    button: "circle",
                    primaryColor: "#2a7de9"
                },
                plugins: {
                    watermark: {
                        file: "https://enrichapps.s3-ap-northeast-1.amazonaws.com/test/final_07.png",
                        opacity: 0.50,
                        xpos: 50,
                        ypos: 50
                    },
                    thumbnails: {
                        0: {
                            src: 'https://fastcpd.s3.ap-northeast-1.amazonaws.com/courses/jpeg/1591253378576946_d5d0_3.jpg',
                            style: {
                            left: '-60px',
                            width: '600px',
                            height: '50px',
                            clip: 'rect(0, 120px, 50px, 0)'
                            }
                        },
                        10: {
                            style: {
                            left: '-180px',
                            clip: 'rect(0, 240px, 50px, 120px)'
                            }
                        },
                        20: {
                            style: {
                            left: '-300px',
                            clip: 'rect(0, 360px, 50px, 240px)'
                            }
                        },
                        30: {
                            style: {
                            left: '-420px',
                            clip: 'rect(0, 480px, 50px, 360px)'
                            }
                        },
                        40: {
                            style: {
                            left: '-540px',
                            clip: 'rect(0, 600px, 50px, 480px)'
                            }
                        }
                    }
                }
            };
        </script>
        <script data-cfasync="false" src="https://assets.swarmcdn.com/cross/swarmdetect.js"></script>
    </head>
    <body>
    <!-- <smartvideo src="https://enrichapps.s3-ap-northeast-1.amazonaws.com/test/Gronk's+FIRST+Big+Game!+(Patriots+vs.+Steelers%2C+2010).mp4" width="1280" height="720" class="swarm-fluid" controls></smartvideo> -->
    <!-- <smartvideo src="https://enrichapps.s3-ap-northeast-1.amazonaws.com/test/DOH+press+briefing+on+PH+coronavirus+cases+_+Tuesday%2C+April+7.mp4"width="1280" height="720" class="swarm-fluid" controls></smartvideo> -->
    <smartvideo src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/test/Screencast+2019-12-09+16_55_52.avi" poster="https://enrichapps.s3-ap-northeast-1.amazonaws.com/test/final_07.png" loop autoplay width="200" height="200" class="swarm-fluid" controls></smartvideo>
    <!-- <iframe src="https://player.vimeo.com/video/405311141" width="1280" height="720" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe> -->
    <!-- <iframe src=“https://player.vimeo.com/video/405310094” width="1280" height="720" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe> -->
        <!-- https://support.swarmify.com/hc/en-us/articles/360043738673 -->
        <!-- https://docs.swarmify.com/docs/frequently-asked-questions -->
        <!-- blob:chrome-extension://ckblfoghkjhaclegefojbgllenffajdc/1d659dfe-8e39-4457-974f-314c512c0b82 -->
    </body>
</html>


