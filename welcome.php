<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'poppins', sans-serif;
        }

        body {
            background: #141E30;
            background: -webkit-linear-gradient(to top, #243B55, #141E30);


        }

        .action {
            position: fixed;
            top: 20px;
            right: 30px;
        }

        .action .profile {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        /* .action.profile i {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
} */

        .action .menu {
            position: absolute;
            top: 120px;
            right: -10px;
            padding: 10px 20px;
            background: #fff;
            width: 200px;
            box-sizing: 0 5px 25px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            transition: 0.5s;
            visibility: hidden;
            opacity: 0;
        }

        .action .menu.active {
            top: 80px;
            visibility: visible;
            opacity: 1;
        }

        .action .menu::before {
            content: '';
            position: absolute;
            top: -5px;
            right: 30px;
            width: 20px;
            height: 20px;
            background: #fff;
            transform: rotate(45deg);
        }

        .action .menu h3 {
            width: 100%;
            text-align: center;
            font-size: 18px;
            padding: 20px 0;
            font-weight: 500;
            font-size: 18px;
            color: #555;
            line-height: 1.2em;
        }

        .action .menu h3 span {
            font-size: 14px;
            color: #cecece;
            font-weight: 400;
        }

        .action .menu ul li {
            list-style: none;
            padding: 10px 0;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* .action .menu ul li i {
    max-width: 20px;
    margin-right: 10px;
    opacity: 0.5;
    transition: 0.5s;
} */

        /* .action .menu ul li:hover i {
    opacity: 1;
} */

        .action .menu ul li a {
            display: inline-block;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: 0.5s;
        }

        .action .menu ul li:hover a {
            color: #141E30;

        }

        .my-5 {
            color: #fff;
            text-align: center;
            margin-top: 10%;
        }

        .verify {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 3%;
        }

        .verify_txt {
            text-align: center;
            display: block;
            margin-left: auto;
            margin-right: auto;
            color: #fff;
            font-size: 22px;
            margin-top: 1%;
        }
    </style>
</head>

<body>
        <div class="action">
            <div class="profile" onclick="menuToggle();">
                <img src="data:image/jpeg;base64,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" alt="" />
            </div>
            <div class="menu">
                <h3><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b><br><br><span><i class="fas fa-check"></i> Verified User</span></h3>
                <ul>
                    <li><i class="fas fa-user-cog"> <a href="reset-password.php"> Pass Reset</a></i></li>
                    <li><i class="fas fa-sign-out-alt"> <a href="logout.php"> Logout</a></i></li>
                </ul>
            </div>
        </div>
        
        <h1 class="my-5">Congratulations 🎉, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome On Board.</h1>
        <img src="https://media.giphy.com/media/hvMz6f9pEqCMFHYqGg/giphy.gif" alt="" height="80px" width="80px" class="verify"><span class="verify_txt">User Verified</span>
    
    <script>
        function menuToggle() {
            const toggleMenu = document.querySelector('.menu');
            toggleMenu.classList.toggle('active')
        }
    </script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://threejs.org/examples/js/libs/stats.min.js"></script>
    <script src="assets/js/background.js"></script>
</body>

</html>