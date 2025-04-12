<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Подтверждение email</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
      background: #ffffff;
      padding: 20px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #333;
    }
    p {
      font-size: 16px;
      color: #666;
    }
    .button {
      display: inline-block;
      background-color: #007bff;
      color: #ffffff;
      padding: 12px 20px;
      text-decoration: none;
      font-size: 16px;
      border-radius: 5px;
      margin-top: 20px;
    }
    .button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Подтверждение email</h1>
  <p>{{ $code }}</p>
</div>
</body>
</html>
