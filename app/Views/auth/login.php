<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Login | E-Learning Resource Reservation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      overflow-y: auto;
    }

    /* Animated background shapes */
    .shape {
      position: fixed;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      pointer-events: none;
      z-index: 0;
    }

    .shape-1 {
      width: 400px;
      height: 400px;
      top: -200px;
      left: -200px;
      animation: float 20s infinite ease-in-out;
    }

    .shape-2 {
      width: 600px;
      height: 600px;
      bottom: -300px;
      right: -300px;
      animation: float 25s infinite ease-in-out reverse;
    }

    .shape-3 {
      width: 300px;
      height: 300px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      animation: pulse 15s infinite ease-in-out;
      opacity: 0.05;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      33% { transform: translate(40px, -40px) rotate(120deg); }
      66% { transform: translate(-30px, 30px) rotate(240deg); }
    }

    @keyframes pulse {
      0%, 100% { transform: translate(-50%, -50%) scale(1); }
      50% { transform: translate(-50%, -50%) scale(1.2); }
    }

    .login-wrapper {
      width: 100%;
      max-width: 560px;
      margin: auto;
      position: relative;
      z-index: 10;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(10px);
      border-radius: 2.5rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      padding: 2.5rem 2rem;
      width: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    @media (min-width: 640px) {
      .login-card {
        padding: 3rem 2.5rem;
      }
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      font-weight: 600;
      transition: all 0.3s ease;
      border-radius: 1.2rem;
      padding: 1rem;
      position: relative;
      overflow: hidden;
      width: 100%;
      border: none;
      cursor: pointer;
      font-size: 1rem;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.5);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-primary:focus {
      outline: none;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.3);
    }

    /* Fixed Input Wrapper with proper icon alignment */
    .input-wrapper {
      position: relative;
      margin-bottom: 1.5rem;
      display: flex;
      flex-direction: column;
    }

    .input-wrapper label {
      display: block;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #64748b;
      margin-bottom: 0.5rem;
      margin-left: 0.5rem;
    }

    .input-container {
      position: relative;
      width: 100%;
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1.1rem;
      transition: color 0.3s ease;
      z-index: 10;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 1.5rem;
      pointer-events: none;
    }

    .input-container input {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid #e2e8f0;
      border-radius: 1.2rem;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background: white;
      line-height: 1.5;
      height: 3.5rem;
    }

    .input-container input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .input-container input:focus + .input-icon {
      color: #667eea;
    }

    .input-container input::placeholder {
      color: #cbd5e1;
      font-weight: 400;
    }

    /* Password wrapper for toggle button */
    .password-wrapper {
      position: relative;
      width: 100%;
    }

    .password-wrapper input {
      width: 100%;
      padding: 1rem 3rem 1rem 3rem !important;
    }

    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      padding: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.3s ease;
      z-index: 20;
      width: 2rem;
      height: 2rem;
    }

    .password-toggle:hover {
      color: #667eea;
    }

    .password-toggle:focus {
      outline: none;
      color: #667eea;
    }

    .password-toggle i {
      font-size: 1.2rem;
    }

    .logo-circle {
      width: 90px;
      height: 90px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.5);
      position: relative;
      animation: pulse-logo 2s infinite;
    }

    @keyframes pulse-logo {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .logo-circle i {
      font-size: 2.5rem;
      color: white;
      animation: spin-slow 10s linear infinite;
    }

    @keyframes spin-slow {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-header h1 {
      font-size: 1.8rem;
      font-weight: 800;
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.25rem;
      letter-spacing: -0.02em;
    }

    .login-header p {
      font-size: 0.9rem;
      color: #64748b;
      font-weight: 500;
    }

    .text-link {
      color: #667eea;
      font-weight: 600;
      transition: color 0.3s ease;
      text-decoration: none;
      position: relative;
      font-size: 0.9rem;
    }

    .text-link::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      transition: width 0.3s ease;
    }

    .text-link:hover::after {
      width: 100%;
    }

    .text-link:hover {
      color: #764ba2;
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
    }

    .checkbox-custom {
      appearance: none;
      width: 1.2rem;
      height: 1.2rem;
      border: 2px solid #e2e8f0;
      border-radius: 0.3rem;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      flex-shrink: 0;
    }

    .checkbox-custom:checked {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-color: transparent;
    }

    .checkbox-custom:checked::after {
      content: '\f00c';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      color: white;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 0.7rem;
    }

    .checkbox-custom:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .error-message {
      background: #fef2f2;
      border: 1px solid #fee2e2;
      border-radius: 1.2rem;
      padding: 1rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: #991b1b;
      font-weight: 500;
      animation: slideIn 0.3s ease;
    }

    .error-message i {
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .success-message {
      background: #f0fdf4;
      border: 1px solid #dcfce7;
      border-radius: 1.2rem;
      padding: 1rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: #166534;
      font-weight: 500;
      animation: slideIn 0.3s ease;
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #e2e8f0;
    }

    .divider span {
      padding: 0 1rem;
      color: #94a3b8;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .welcome-text {
      text-align: center;
      margin-bottom: 2rem;
      padding: 1rem;
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      border-radius: 1.2rem;
    }

    .welcome-text h3 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 0.25rem;
    }

    .welcome-text p {
      font-size: 0.85rem;
      color: #64748b;
    }

    .footer-links {
      margin-top: 1.5rem;
      text-align: center;
    }

    .footer-links p {
      color: #94a3b8;
      font-size: 0.7rem;
      line-height: 1.5;
    }

    .footer-links a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.7rem;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.4);
    }
  </style>
</head>
<body>

  <!-- Animated Background Shapes -->
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  <div class="shape shape-3"></div>

  <!-- Login Container -->
  <div class="login-wrapper">
    <div class="login-card">
      
      <!-- Logo and Header -->
      <div class="login-header">
        <div class="logo-circle">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h1>Welcome Back!</h1>
        <p>Brgy. F De Jesus, Unisan Quezon</p>
      </div>

      <!-- Welcome Message -->
      <div class="welcome-text">
        <h3>E-Learning Resource Reservation System</h3>
        <p>Sign in to access your account and manage reservations</p>
      </div>

      <!-- Error Message -->
      <?php if(session()->getFlashdata('error')): ?>
        <div class="error-message">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span><?= session()->getFlashdata('error') ?></span>
        </div>
      <?php endif; ?>

      <?php if(session()->getFlashdata('success')): ?>
        <div class="success-message">
          <i class="fa-solid fa-circle-check"></i>
          <span><?= session()->getFlashdata('success') ?></span>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="/login-action" method="post">
        <?= csrf_field() ?>
        
        <!-- Email Field -->
        <div class="input-wrapper">
          <label for="email">Email Address</label>
          <div class="input-container">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" 
                   placeholder="your.email@example.com" 
                   value="<?= old('email') ?>"
                   required>
          </div>
        </div>

        <!-- Password Field -->
        <div class="input-wrapper">
          <label for="password">Password</label>
          <div class="input-container">
            <i class="fa-solid fa-lock input-icon"></i>
            <div class="password-wrapper">
              <input type="password" id="password" name="password" 
                     placeholder="••••••••"
                     required>
              <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                <i class="fa-regular fa-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
          <label class="checkbox-wrapper">
            <input type="checkbox" name="remember" class="checkbox-custom" id="remember">
            <span class="text-gray-600 font-medium text-sm">Remember me</span>
          </label>
          <a href="/forgot-password" class="text-link">Forgot password?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary">
          <i class="fa-regular fa-paper-plane mr-2"></i> Sign In
        </button>

        <!-- Divider -->
        <div class="divider">
          <span>or</span>
        </div>

        <!-- Register Link -->
        <div class="text-center text-sm">
          <span class="text-gray-500">New to our system?</span>
          <a href="/register" class="text-link ml-1">Create an account</a>
        </div>

        <!-- Optional: Social Login -->
        <button type="button" class="btn-primary !bg-white !text-gray-700 border-2 border-gray-200 mt-4 hover:border-purple-300" onclick="window.location.href='/auth/google'">
          <i class="fa-brands fa-google text-red-500 mr-2"></i>
          <span>Continue with Google</span>
        </button>
      </form>

      <!-- Footer -->
      <div class="footer-links">
        <p>
          By signing in, you agree to our 
          <a href="/terms">Terms of Service</a> 
          and 
          <a href="/privacy">Privacy Policy</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    // Password visibility toggle
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fa-regular fa-eye-slash';
      } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fa-regular fa-eye';
      }
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      
      if (!email || !password) {
        e.preventDefault();
        alert('Please fill in all fields');
      }
    });

    // Auto-hide error messages after 5 seconds
    setTimeout(function() {
      const errorMsg = document.querySelector('.error-message');
      const successMsg = document.querySelector('.success-message');
      
      if (errorMsg) {
        errorMsg.style.transition = 'opacity 0.5s ease';
        errorMsg.style.opacity = '0';
        setTimeout(() => errorMsg.remove(), 500);
      }
      
      if (successMsg) {
        successMsg.style.transition = 'opacity 0.5s ease';
        successMsg.style.opacity = '0';
        setTimeout(() => successMsg.remove(), 500);
      }
    }, 5000);
  </script>
</body>
</html>