<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Clinical Monitoring System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color:#CB7F41;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color:#CB7F41;
            padding: 0 25px;
            font-size: 30px;
            font-weight: 60;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .links>a:hover{
            display: inline-block;
             color: white;
              background: green;
              border: 1px ;
              padding: 12px 30px;
               border-radius: 8px;
               text-transform: uppercase;
               transition: all .60s ease-in-out;
        }
        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
              Sliver Maple Student Clinical <br> Monitoring System
            </div>

            <div class="links">
			
                <a href="Admin/index.php">Admin Log In</a>
                <a href="Student/index.php">Student Log In</a>
                <a href="Supervisor/index.php">Instructor Log In</a>
            </div>
        </div>
    </div>
</body>

</html>