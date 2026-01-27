<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - E-Learning Resource Reservation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #3b82f6);
    }

    .login-card {
      background-color: white;
      border-radius: 1.5rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      padding: 3rem 2.5rem;
      width: 100%;
      max-width: 520px;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .login-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .btn-primary {
      background: linear-gradient(90deg, #4f46e5, #1e40af);
      color: white;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #4338ca, #1e3a8a);
    }

    .input-focus:focus {
      outline: none;
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
    }

    .login-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 2rem;
    }

    .login-header h1 {
      font-size: 1.5rem;
      font-weight: 600;
      text-align: center;
      color: #1e3a8a;
      margin-bottom: 0.25rem;
    }

    .login-header p {
      font-size: 0.9rem;
      color: #3b4252;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .text-link {
      color: #4f46e5;
      transition: color 0.3s;
    }

    .text-link:hover {
      color: #4338ca;
      text-decoration: underline;
    }

    .icon-circle {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #3b82f6, #4f46e5);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      box-shadow: 0 4px 15px rgba(79,70,229,0.3);
    }

    .icon-circle i {
      font-size: 2.5rem;
      color: white;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

  <div class="login-card">
    <div class="login-header">
      <div class="icon-circle">
        <i class="fas fa-book"></i>
      </div>
      <h1>E-Learning Resource Reservation System</h1>
      <p>Brgy. F De Jesus, Unisan Quezon</p>
    </div>

    <h2 class="text-lg font-medium text-gray-900 mb-4 text-center">Sign in</h2>

    <?php if(session()->getFlashdata('error')): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <form action="/login-action" method="post" class="space-y-4">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
      </div>

      <div class="flex justify-between items-center text-sm text-gray-600">
        <label class="flex items-center gap-2">
          <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300">
          Remember me
        </label>
        <a href="/forgot-password" class="text-link">Forgot password?</a>
      </div>

      <button type="submit" class="w-full py-2 rounded-md btn-primary">Next</button>

      <div class="text-center mt-3 text-sm text-gray-600">
        Don't have an account? 
        <a href="/register" class="text-link">Register</a>
      </div>
    </form>
  </div>

</body>
</html>
