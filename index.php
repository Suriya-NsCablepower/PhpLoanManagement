
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        
body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            text-align: center;
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 50%;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
            animation: moveBorder 4s infinite;
        }

        @keyframes moveBorder {
            0% {
                border-top: 5px solid #ffcc00;
                border-right: 5px solid #00ccff;
                border-bottom: 5px solid #ff9900;
                border-left: 5px solid #00ff00;
            }
            25% {
                border-top: 5px solid #ff9900;
                border-right: 5px solid #ffcc00;
                border-bottom: 5px solid #00ccff;
                border-left: 5px solid #00ff00;
            }
            50% {
                border-top: 5px solid #00ccff;
                border-right: 5px solid #ff9900;
                border-bottom: 5px solid #00ff00;
                border-left: 5px solid #ffcc00;
            }
            75% {
                border-top: 5px solid #00ff00;
                border-right: 5px solid #00ccff;
                border-bottom: 5px solid #ffcc00;
                border-left: 5px solid #ff9900;
            }
            100% {
                border-top: 5px solid #ffcc00;
                border-right: 5px solid #00ccff;
                border-bottom: 5px solid #ff9900;
                border-left: 5px solid #00ff00;
            }
        }

        .container:before {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, #ff6b6b, #ffcc00, #00c6ff, #1e3c72, #ff6b6b);
            background-size: 400% 400%;
            border-radius: 15px;
            z-index: -1;
            animation: gradient-border 5s ease infinite;
        }

        @keyframes gradient-border {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container img {
            width: 100px;
            margin-bottom: 20px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            text-align: center;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        input:focus {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.8);
            outline: none;
        }

        .btn {
            background: #ffcc00;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s, transform 0.2s ease-in-out;
        }

        .btn:hover {
            background: #ff9900;
            transform: scale(1.05);
        }

        .signup-link {
            display: block;
            margin-top: 10px;
            font-size: 1rem;
            color: #ffcc00;
            text-decoration: none;
        }

        .signup-link:hover {
            color: #ff9900;
        }

    </style>
</head>
<body>

    <div class="container">
        <img src="Logo/gifmaker_me.gif" alt="App Logo">
        <h2>Login</h2>

        <form id="login-form" action="Login/login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required autocomplete="username"><br>
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password"><br>
            <button type="submit" class="btn">Login</button>
        </form>

        <a href="Login/signup.html" class="signup-link">Don't have an account? Sign up</a>
    </div>

    

</body>
</html>
