<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Register | E-Learning Resource Reservation System</title>
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

    .register-wrapper {
      width: 100%;
      max-width: 600px;
      margin: auto;
      position: relative;
      z-index: 10;
    }

    .register-card {
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
      .register-card {
        padding: 3rem 2.5rem;
      }
    }

    .register-card:hover {
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

    .input-container input,
    .input-container select {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid #e2e8f0;
      border-radius: 1.2rem;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background: white;
      appearance: none;
      line-height: 1.5;
      height: 3.5rem;
    }

    .input-container select {
      cursor: pointer;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
      background-position: right 1rem center;
      background-repeat: no-repeat;
      background-size: 1.5rem;
      padding-right: 2.5rem;
    }

    .input-container input:focus,
    .input-container select:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .input-container input:focus + .input-icon,
    .input-container select:focus + .input-icon {
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

    .register-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .register-header h1 {
      font-size: 1.8rem;
      font-weight: 800;
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.25rem;
      letter-spacing: -0.02em;
    }

    .register-header p {
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
      cursor: pointer;
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

    .strength-meter {
      margin-top: 0.5rem;
      padding: 0.75rem;
      border-radius: 0.8rem;
      font-size: 0.8rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      animation: slideIn 0.3s ease;
    }

    .strength-meter.weak {
      background: #fef2f2;
      color: #991b1b;
      border: 1px solid #fee2e2;
    }

    .strength-meter.medium {
      background: #fffbeb;
      color: #92400e;
      border: 1px solid #fef3c7;
    }

    .strength-meter.strong {
      background: #f0fdf4;
      color: #166534;
      border: 1px solid #dcfce7;
    }

    .password-requirements {
      background: #f8fafc;
      border-radius: 1rem;
      padding: 1rem;
      margin-top: 0.75rem;
      font-size: 0.8rem;
      border: 1px solid #e2e8f0;
    }

    .password-requirements p {
      color: #64748b;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .requirement {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #94a3b8;
      margin-bottom: 0.25rem;
      padding: 0.25rem 0;
    }

    .requirement i {
      width: 1.2rem;
      font-size: 0.9rem;
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

    /* Terms checkbox */
    .terms-checkbox {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      cursor: pointer;
      margin: 1.5rem 0;
      padding: 0.5rem 0;
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

    /* Modal Styles */
    .modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.65);
      backdrop-filter: blur(6px);
      z-index: 1000;
      padding: 1.5rem;
      overflow-y: auto;
      align-items: center;
      justify-content: center;
    }

    .modal-backdrop.show {
      display: flex;
      animation: fadeIn 0.15s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal-box {
      background: white;
      border-radius: 2.5rem;
      width: 100%;
      max-width: 600px;
      padding: 2.5rem;
      margin: auto;
      animation: slideUp 0.2s ease;
      max-height: 80vh;
      overflow-y: auto;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    @keyframes slideUp {
      from {
        transform: translateY(16px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #e2e8f0;
    }

    .modal-header h3 {
      font-size: 1.5rem;
      font-weight: 800;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #94a3b8;
      transition: color 0.3s ease;
      width: 2.5rem;
      height: 2.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: #f1f5f9;
    }

    .modal-close:hover {
      color: #64748b;
      background: #e2e8f0;
    }

    .modal-content {
      color: #475569;
      line-height: 1.6;
      font-size: 0.95rem;
    }

    .modal-content h4 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #1e293b;
      margin: 1.5rem 0 0.5rem;
    }

    .modal-content h4:first-child {
      margin-top: 0;
    }

    .modal-content p {
      margin-bottom: 1rem;
    }

    .modal-content ul {
      list-style: none;
      padding-left: 0;
      margin-bottom: 1rem;
    }

    .modal-content li {
      margin-bottom: 0.5rem;
      padding-left: 1.5rem;
      position: relative;
    }

    .modal-content li::before {
      content: '•';
      color: #667eea;
      font-weight: bold;
      position: absolute;
      left: 0.5rem;
    }

    .modal-footer {
      margin-top: 2rem;
      padding-top: 1rem;
      border-top: 2px solid #e2e8f0;
      display: flex;
      justify-content: flex-end;
    }

    .modal-btn {
      padding: 0.75rem 2rem;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      font-weight: 600;
      border: none;
      border-radius: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .modal-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.5);
    }
  </style>
</head>
<body>

  <!-- Animated Background Shapes -->
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  <div class="shape shape-3"></div>

  <!-- Terms Modal -->
  <div id="termsModal" class="modal-backdrop" onclick="handleBackdrop(event, 'termsModal')">
    <div class="modal-box">
      <div class="modal-header">
        <h3>Terms of Service</h3>
        <button class="modal-close" onclick="closeModal('termsModal')">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      <div class="modal-content">
        <h4>1. Acceptance of Terms</h4>
        <p>By accessing and using the E-Learning Resource Reservation System, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>
        
        <h4>2. User Accounts</h4>
        <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>
        
        <h4>3. Resource Reservation</h4>
        <ul>
          <li>Reservations are limited to 3 per user within a 2-week period to ensure fair access.</li>
          <li>Resources must be used for their intended educational purposes.</li>
          <li>Cancellations must be made at least 2 hours before the scheduled time.</li>
          <li>No-shows may result in temporary suspension of booking privileges.</li>
        </ul>
        
        <h4>4. Code of Conduct</h4>
        <p>Users must:</p>
        <ul>
          <li>Treat facilities and equipment with respect</li>
          <li>Follow all posted guidelines and staff instructions</li>
          <li>Maintain a quiet and productive learning environment</li>
          <li>Report any issues or damages immediately</li>
        </ul>
        
        <h4>5. Privacy</h4>
        <p>Your use of the system is also governed by our Privacy Policy, which explains how we collect, use, and protect your personal information.</p>
        
        <h4>6. Modifications</h4>
        <p>We reserve the right to modify these terms at any time. Continued use of the system constitutes acceptance of modified terms.</p>
      </div>
      <div class="modal-footer">
        <button class="modal-btn" onclick="acceptTerms()">I Understand</button>
      </div>
    </div>
  </div>

  <!-- Privacy Modal -->
  <div id="privacyModal" class="modal-backdrop" onclick="handleBackdrop(event, 'privacyModal')">
    <div class="modal-box">
      <div class="modal-header">
        <h3>Privacy Policy</h3>
        <button class="modal-close" onclick="closeModal('privacyModal')">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      <div class="modal-content">
        <h4>Information We Collect</h4>
        <ul>
          <li>Personal information (name, email, contact number)</li>
          <li>Account credentials (secured with encryption)</li>
          <li>Reservation history and usage patterns</li>
          <li>Device and browser information for security</li>
        </ul>
        
        <h4>How We Use Your Information</h4>
        <ul>
          <li>To process and manage your reservations</li>
          <li>To communicate important updates and notifications</li>
          <li>To improve our services and user experience</li>
          <li>To ensure compliance with our fair usage policy</li>
          <li>For security and fraud prevention</li>
        </ul>
        
        <h4>Data Protection</h4>
        <p>We implement industry-standard security measures to protect your personal information from unauthorized access, disclosure, or misuse.</p>
        
        <h4>Information Sharing</h4>
        <p>We do not sell or rent your personal information to third parties. Information may be shared only:</p>
        <ul>
          <li>With your explicit consent</li>
          <li>To comply with legal obligations</li>
          <li>To protect rights and safety</li>
        </ul>
        
        <h4>Your Rights</h4>
        <p>You have the right to:</p>
        <ul>
          <li>Access your personal information</li>
          <li>Request corrections to your data</li>
          <li>Delete your account and associated data</li>
          <li>Opt-out of non-essential communications</li>
        </ul>
        
        <h4>Cookies</h4>
        <p>We use essential cookies to maintain your session and preferences. You can disable cookies in your browser settings, but some features may not function properly.</p>
        
        <h4>Contact Us</h4>
        <p>For privacy-related concerns, please contact our data protection officer at privacy@elearning.edu.ph</p>
      </div>
      <div class="modal-footer">
        <button class="modal-btn" onclick="closeModal('privacyModal')">Close</button>
      </div>
    </div>
  </div>

  <!-- Register Container -->
  <div class="register-wrapper">
    <div class="register-card">
      
      <!-- Logo and Header -->
      <div class="register-header">
        <div class="logo-circle">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h1>Create Account</h1>
        <p>Brgy. F De Jesus, Unisan Quezon</p>
      </div>

      <!-- Welcome Message -->
      <div class="welcome-text">
        <h3>Join Our Learning Community</h3>
        <p>Sign up to access resources and manage reservations</p>
      </div>

      <!-- Flash Messages -->
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

      <!-- Registration Form -->
      <form action="/register-action" method="post" id="registerForm">
        <?= csrf_field() ?>
        
        <!-- Full Name -->
        <div class="input-wrapper">
          <label for="name">Full Name</label>
          <div class="input-container">
            <i class="fa-regular fa-user input-icon"></i>
            <input type="text" id="name" name="name" 
                   placeholder="Juan Dela Cruz" 
                   value="<?= old('name') ?>"
                   required>
          </div>
        </div>

        <!-- Email -->
        <div class="input-wrapper">
          <label for="email">Email Address</label>
          <div class="input-container">
            <i class="fa-regular fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" 
                   placeholder="juan@example.com" 
                   value="<?= old('email') ?>"
                   required>
          </div>
        </div>

        <!-- Role -->
        <div class="input-wrapper">
          <label for="role">I am a...</label>
          <div class="input-container">
            <i class="fa-solid fa-users input-icon"></i>
            <select name="role" id="role" required>
              <option value="" disabled selected>Select your role</option>
              <option value="resident" <?= old('role') == 'resident' ? 'selected' : '' ?>>Resident</option>
              <option value="sk" <?= old('role') == 'sk' ? 'selected' : '' ?>>SK Officer</option>
            </select>
          </div>
        </div>

        <!-- Password -->
        <div class="input-wrapper">
          <label for="password">Password</label>
          <div class="input-container">
            <i class="fa-solid fa-lock input-icon"></i>
            <div class="password-wrapper">
              <input type="password" id="password" name="password" 
                     placeholder="Create a strong password"
                     required>
              <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon')" aria-label="Toggle password visibility">
                <i class="fa-regular fa-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>
          
          <!-- Password Requirements -->
          <div class="password-requirements">
            <p>Password must contain:</p>
            <div class="requirement" id="req-length">
              <i class="fa-regular fa-circle"></i> At least 8 characters
            </div>
            <div class="requirement" id="req-uppercase">
              <i class="fa-regular fa-circle"></i> One uppercase letter
            </div>
            <div class="requirement" id="req-lowercase">
              <i class="fa-regular fa-circle"></i> One lowercase letter
            </div>
            <div class="requirement" id="req-number">
              <i class="fa-regular fa-circle"></i> One number
            </div>
            <div class="requirement" id="req-special">
              <i class="fa-regular fa-circle"></i> One special character
            </div>
          </div>
        </div>

        <!-- Confirm Password -->
        <div class="input-wrapper">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-container">
            <i class="fa-solid fa-lock input-icon"></i>
            <div class="password-wrapper">
              <input type="password" id="confirm_password" name="confirm_password" 
                     placeholder="Re-enter your password"
                     required>
              <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'toggleConfirmIcon')" aria-label="Toggle password visibility">
                <i class="fa-regular fa-eye" id="toggleConfirmIcon"></i>
              </button>
            </div>
          </div>
          <div id="password-match" class="strength-meter" style="display: none;"></div>
        </div>

        <!-- Terms and Conditions -->
        <label class="terms-checkbox">
          <input type="checkbox" name="terms" class="checkbox-custom" required>
          <span class="text-sm text-gray-600">
            I agree to the 
            <a href="#" class="text-link" onclick="openModal('termsModal'); return false;">Terms of Service</a> 
            and 
            <a href="#" class="text-link" onclick="openModal('privacyModal'); return false;">Privacy Policy</a>
          </span>
        </label>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary" id="submitBtn">
          <i class="fa-regular fa-paper-plane mr-2"></i> Create Account
        </button>

        <!-- Divider -->
        <div class="divider">
          <span>or</span>
        </div>

        <!-- Login Link -->
        <div class="text-center text-sm">
          <span class="text-gray-500">Already have an account?</span>
          <a href="/login" class="text-link ml-1">Sign In</a>
        </div>

        <!-- Social Registration -->
        <button type="button" class="btn-primary !bg-white !text-gray-700 border-2 border-gray-200 mt-4 hover:border-purple-300" onclick="window.location.href='/auth/google'">
          <i class="fa-brands fa-google text-red-500 mr-2"></i>
          <span>Sign up with Google</span>
        </button>
      </form>

      <!-- Footer -->
      <div class="footer-links">
        <p>
          By creating an account, you agree to our 
          <a href="#" class="text-link" onclick="openModal('termsModal'); return false;">Terms of Service</a> 
          and 
          <a href="#" class="text-link" onclick="openModal('privacyModal'); return false;">Privacy Policy</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    // Modal functions
    function openModal(modalId) {
      document.getElementById(modalId).classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.remove('show');
      document.body.style.overflow = '';
    }

    function handleBackdrop(event, modalId) {
      if (event.target === document.getElementById(modalId)) {
        closeModal(modalId);
      }
    }

    function acceptTerms() {
      closeModal('termsModal');
      // Optionally auto-check the terms checkbox
      document.querySelector('input[name="terms"]').checked = true;
    }

    // Password visibility toggle
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
      } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
      }
    }

    // Password strength check
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const matchDiv = document.getElementById('password-match');
    const submitBtn = document.getElementById('submitBtn');

    // Requirements elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    function updatePasswordRequirements(password) {
      const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
      };

      // Update requirement icons
      reqLength.innerHTML = requirements.length 
        ? '<i class="fa-solid fa-check-circle text-green-500"></i> At least 8 characters'
        : '<i class="fa-regular fa-circle"></i> At least 8 characters';
      
      reqUppercase.innerHTML = requirements.uppercase
        ? '<i class="fa-solid fa-check-circle text-green-500"></i> One uppercase letter'
        : '<i class="fa-regular fa-circle"></i> One uppercase letter';
      
      reqLowercase.innerHTML = requirements.lowercase
        ? '<i class="fa-solid fa-check-circle text-green-500"></i> One lowercase letter'
        : '<i class="fa-regular fa-circle"></i> One lowercase letter';
      
      reqNumber.innerHTML = requirements.number
        ? '<i class="fa-solid fa-check-circle text-green-500"></i> One number'
        : '<i class="fa-regular fa-circle"></i> One number';
      
      reqSpecial.innerHTML = requirements.special
        ? '<i class="fa-solid fa-check-circle text-green-500"></i> One special character'
        : '<i class="fa-regular fa-circle"></i> One special character';

      // Return strength level
      const metCount = Object.values(requirements).filter(Boolean).length;
      if (metCount === 5) return 'strong';
      if (metCount >= 3) return 'medium';
      return 'weak';
    }

    password.addEventListener('input', () => {
      updatePasswordRequirements(password.value);
      
      // Check if passwords match
      if (confirmPassword.value) {
        if (confirmPassword.value !== password.value) {
          matchDiv.style.display = 'flex';
          matchDiv.className = 'strength-meter weak';
          matchDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Passwords do not match';
          submitBtn.disabled = true;
        } else {
          matchDiv.style.display = 'flex';
          matchDiv.className = 'strength-meter strong';
          matchDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i> Passwords match';
          submitBtn.disabled = false;
        }
      }
    });

    confirmPassword.addEventListener('input', () => {
      if (confirmPassword.value !== password.value) {
        matchDiv.style.display = 'flex';
        matchDiv.className = 'strength-meter weak';
        matchDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Passwords do not match';
        submitBtn.disabled = true;
      } else {
        matchDiv.style.display = 'flex';
        matchDiv.className = 'strength-meter strong';
        matchDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i> Passwords match';
        submitBtn.disabled = false;
      }
    });

    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const role = document.getElementById('role').value;
      const password = document.getElementById('password').value;
      const confirm = document.getElementById('confirm_password').value;
      const terms = document.querySelector('input[name="terms"]').checked;

      if (!name || !email || !role || !password || !confirm) {
        e.preventDefault();
        alert('Please fill in all fields');
        return;
      }

      if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match');
        return;
      }

      if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long');
        return;
      }

      if (!terms) {
        e.preventDefault();
        alert('Please agree to the Terms of Service and Privacy Policy');
        return;
      }
    });

    // Auto-hide flash messages after 5 seconds
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

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal('termsModal');
        closeModal('privacyModal');
      }
    });
  </script>
</body>
</html>