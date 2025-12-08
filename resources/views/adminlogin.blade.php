
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #4A00E0, #8E2DE2);
        }

        .login-container {
            background: white;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #8E2DE2;
            outline: none;
            box-shadow: 0 0 5px rgba(142, 45, 226, 0.5);
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: #8E2DE2;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background: #6A1B9A;
        }

        /* Popup Styling */
        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .popup-content p {
            margin-bottom: 20px;
            color: red;
            font-size: 16px;
        }

        .popup-button {
            background: #8E2DE2;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 6px;
        }

        .popup-button:hover {
            background: #6A1B9A;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-button">Login</button>
            </form>
            </div>

    <!-- Popup Notification -->
    @if ($errors->any())
    <div id="errorPopup" class="popup">
        <div class="popup-content">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            <button class="popup-button" onclick="closePopup()">Tutup</button>
        </div>
    </div>
    @endif

    <script>
        function closePopup() {
            document.getElementById("errorPopup").style.display = "none";
        }

        // Jika ada error, tampilkan popup
        @if ($errors->any())
            document.getElementById("errorPopup").style.display = "flex";
        @endif
    </script>
</body>

</html>
