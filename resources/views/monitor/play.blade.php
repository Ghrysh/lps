<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Greeting</title>
</head>

<body style="margin:0;background:black">

    <video id="video" autoplay muted playsinline style="width:100vw;height:100vh;object-fit:contain;background:black">
        <source src="{{ asset('assets/video/greeting.mp4') }}" type="video/mp4">
    </video>

    <script>
        setInterval(() => {
            fetch('/monitor/status/{{ $token }}')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'skipped') {
                        window.location.href = '/monitor';
                    }
                });
        }, 1000);
    </script>

</body>

</html>
