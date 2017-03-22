<html style="width: 100%; min-height: 100%; margin: 0;">
<head>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,600">
</head>
<body style="margin: 0; padding-top: 40px; padding-bottom: 1px; width: 100%; height: 100%;
        font-family: RobotoDraft, sans-serif; color: #333333;">
<div style="font-family: arial, sans-serif; min-height: 100%;">
    <div style="width: 420px; background-color: white; position: relative;
                top: 0; left: 0; right: 0; padding: 20px 20px 0; margin: auto auto 70px;">
        <div style="position: relative; text-align: center;">
            <img src="{{asset("/assets/img/memori.png")}}" height= "85"
                 style="margin-bottom:5px;" alt="Memory logo">
        </div>
        <div style="position: relative; margin-top: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 600; font-family: RobotoDraft, sans-serif; color: #333333;">
                @yield('title')
            </h1>
        </div>
        <div style="font-size: 110%; padding-top: 10px;">
            @yield('body')
        </div>
        <div style="font-size: 10px; display: block; padding: 45px 0 5px; position: relative;">
            <p style="font-family: RobotoDraft, sans-serif; color: #53585e; opacity: 0.9; font-weight: 300; margin: 0;
                      text-align: center;"><a href="http://www.scify.gr/site/en/">www.scify.org</a></p>
        </div>
    </div>
</div>
</body>
</html>