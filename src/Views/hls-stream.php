<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
    <style>
* {box-sizing: border-box;}
body {font-family: Verdana, sans-serif;}
.mySlides {display: none;}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active {
  background-color: #717171;
}

/* Fading animation */
.fade {
  animation-name: fade;
  animation-duration: 1.5s;
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .text {font-size: 11px}
}
</style>
</head>
<body>

<div class="slideshow-container">

    <?php 
    var_dump($a);
    foreach($a as $i){?>
      <div class="mySlides fade">
        <div class="numbertext">1 / 3</div>
        <img src="<?php echo ROOTURL.'/public/media/a/'.$i;?>" style="width:100%">
      </div>

    <?php }?>
    </div>
    <script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
      let i;
      let slides = document.getElementsByClassName("mySlides");
      let dots = document.getElementsByClassName("dot");
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
      }
      slideIndex++;
      if (slideIndex > slides.length) {slideIndex = 1}    
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex-1].style.display = "block";  
      dots[slideIndex-1].className += " active";
      setTimeout(showSlides, 2000); // Change image every 2 seconds
    }
    </script>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
<!-- Or if you want the latest version from the main branch -->
<!-- <script src="https://cdn.jsdelivr.net/npm/hls.js@canary"></script> -->
<video id="video" controls></video>
<script>
  var video = document.getElementById('video');
  var videoSrc = 'http://localhost/download-file/public/out/hls-stream_360p.m3u8';
  if (Hls.isSupported()) {
    var hls = new Hls();
    hls.loadSource(videoSrc);
    hls.attachMedia(video);
  }
  // HLS.js is not supported on platforms that do not have Media Source
  // Extensions (MSE) enabled.
  //
  // When the browser has built-in HLS support (check using `canPlayType`),
  // we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video
  // element through the `src` property. This is using the built-in support
  // of the plain video element, without using HLS.js.
  //
  // Note: it would be more normal to wait on the 'canplay' event below however
  // on Safari (where you are most likely to find built-in HLS support) the
  // video.src URL must be on the user-driven white-list before a 'canplay'
  // event will be emitted; the last video event that can be reliably
  // listened-for when the URL is not on the white-list is 'loadedmetadata'.
  else if (video.canPlayType('application/vnd.apple.mpegurl')) {
    video.src = videoSrc;
  }
</script>

<div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/8.0.2/video-js.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/8.0.2/video.min.js"></script>
<script src="http://localhost/download-file/public/js/dash/dash.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/5.1.1/videojs-dash.min.js"></script>
<script>
  var player = videojs('example-video');
  player.src({ src: 'http://localhost/download-file/public/media/dash/out.mpd', type: 'application/dash+xml'});
  player.play();
</script>
<video-js id=vid1 width=600 height=300 class="vjs-default-skin" controls>
  <source
     src="https://www.googleapis.com/drive/v3/files/1P5d5c9OU_CQ2x6MLVKsIE0CKfEh4OVUD?alt=media&key=AIzaSyCst0v4lIB1Zs1cwcs98-Lki8F8e5o-hFA"
     type="application/x-mpegURL">
</video-js>
<script src="https://unpkg.com/browse/@videojs/http-streaming@2.15.1/dist/videojs-http-streaming.js"></script>
<script>
var player = videojs('vid1');
player.play();
</script>