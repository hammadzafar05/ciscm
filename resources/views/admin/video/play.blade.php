<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{  __lang('Video')  }}: {{ $video->name  }}</title>
    <link href="{{ basePath() }}/client/vendor/bootstrap4/bootstrap.min.css" rel="stylesheet"><!-- https://getbootstrap.com -->
    <link href="{{ basePath() }}/client/vendor/videojs/video-js.css" rel="stylesheet"><!-- https://videojs.com -->

    <style type="text/css">
        .video-js {
            font-size: 1rem;
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="card" style="margin: 30px; margin-top: 10px;" >
        <div class="embed-responsive embed-responsive-16by9">
            <video id="video" @php  if (!empty($poster)):  @endphp poster="{{ $poster }}?rand={{ time() }}"  @php  endif;  @endphp class="embed-responsive-item video-js vjs-default-skin" width="640" height="360"  controls>
            </video>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $video->name  }}</h5>
            <p class="card-text">{{ $video->description  }}</p>
            @php  if(isset($_SERVER['HTTP_REFERER'])): @endphp
            <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">{{  __lang('Go Back')  }}</a>
            @php  endif;  @endphp
        </div>
    </div>


</div>


<script src="@php  echo basePath();  @endphp/themes/cpanel/vendors/jquery/dist/jquery.min.js"></script>


<script src="@php  echo basePath();  @endphp/client/vendor/videojs/video.js"></script>
<script>

    var player = videojs('video');

    player.src({
        src: "{{ $videoUrl  }}",
        type: '{{ $type  }}'
    });
    jQuery('.video-js').bind('contextmenu',function() { return false; });
</script>

</body>
</html>

