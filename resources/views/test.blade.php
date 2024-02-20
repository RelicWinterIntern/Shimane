<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>

  <body>
    <button onclick="getNow()">押してね！</button>
    <script>
        const getNow = () => {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var geo_text = "緯度:" + position.coords.latitude + "\n";
                geo_text += "経度:" + position.coords.longitude + "\n";
                alert(geo_text);

                position._token = '{{ csrf_token() }}'

                $.ajax({
                  url: '/coordinate',
                  type: 'POST',
                  data: position,
                  success: function(response) {
                      console.log(response);
                  },
                  error: function(xhr, status, error) {
                      console.error(error);
                  }
                });
            },
            function (error) {
                alert("エラーです！");
            });
        };
    </script>
  </body>

</html>