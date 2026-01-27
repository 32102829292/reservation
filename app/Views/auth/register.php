<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - E-Learning Resource Reservation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #3b82f6, #6366f1, #4f46e5);
    }
    .card {
      background-color: white;
      border-radius: 1.5rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      padding: 3rem 2.5rem;
      width: 100%;
      max-width: 520px;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card:hover {
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
    .text-link { color: #4f46e5; transition: color 0.3s; }
    .text-link:hover { color: #4338ca; text-decoration: underline; }

    /* Password strength indicator */
    .strength {
      font-size: 0.85rem;
      margin-top: 0.25rem;
      min-height: 1rem;
      opacity: 0;
      transform: translateX(-5px);
      transition: all 0.3s ease;
    }
    .strength.show { opacity: 1; transform: translateX(0); }
    .strength.weak { color: #ef4444; }
    .strength.medium { color: #f59e0b; }
    .strength.strong { color: #10b981; }
    .strength.shake { animation: shake 0.3s; }

    @keyframes shake {
      0% { transform: translateX(-5px); }
      25% { transform: translateX(5px); }
      50% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
      100% { transform: translateX(0); }
    }

    .password-instructions {
      font-size: 0.8rem;
      color: #4b5563;
      margin-bottom: 0.5rem;
    }

    /* Show/hide password icon */
    .password-wrapper {
      position: relative;
    }
    .password-wrapper i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #6b7280;
    }
    .password-wrapper i:hover {
      color: #4f46e5;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

  <div class="card">
    <div class="text-center">
      <div class="icon-circle">
        <i class="fas fa-book"></i>
      </div>
      <h1 class="text-2xl font-semibold text-gray-900 mb-1">E-Learning Resource Reservation System</h1>
      <p class="text-gray-600 mb-6">Brgy. F De Jesus, Unisan Quezon</p>
    </div>

    <form action="/register-action" method="post" class="space-y-4">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input type="text" name="name" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select name="role" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
          <option value="resident">Resident</option>
          <option value="sk">SK Officer</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="password-instructions">
          At least 6 chars, include uppercase, lowercase, and number.
        </div>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
          <i class="fas fa-eye" id="togglePassword"></i>
        </div>
        <div id="password-strength" class="strength"></div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <div class="password-wrapper">
          <input type="password" id="confirm_password" name="confirm_password" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md input-focus">
          <i class="fas fa-eye" id="toggleConfirm"></i>
        </div>
        <div id="confirm-strength" class="strength"></div>
      </div>

      <button type="submit" class="w-full py-2 rounded-md btn-primary">Register</button>

      <div class="text-center mt-3 text-sm text-gray-600">
        Already have an account? 
        <a href="/login" class="text-link">Login</a>
      </div>
    </form>
  </div>

  <script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const strengthText = document.getElementById('password-strength');
    const confirmText = document.getElementById('confirm-strength');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirm = document.getElementById('toggleConfirm');

    togglePassword.addEventListener('click', () => {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      togglePassword.classList.toggle('fa-eye-slash');
    });

    toggleConfirm.addEventListener('click', () => {
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
      toggleConfirm.classList.toggle('fa-eye-slash');
    });
    function checkPasswordStrength(val) {
      const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      const mediumRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;

      if(strongRegex.test(val)) return {text: 'Strong - Good job!', className:'strong'};
      else if(mediumRegex.test(val)) return {text: 'Medium - Add special char for more strength.', className:'medium'};
      else return {text: 'Weak - Use uppercase, number, more chars.', className:'weak shake'};
    }

    password.addEventListener('input', () => {
      const result = checkPasswordStrength(password.value);
      strengthText.textContent = result.text;
      strengthText.className = `strength show ${result.className}`;
    });

    confirmPassword.addEventListener('input', () => {
      if(confirmPassword.value !== password.value) {
        confirmText.textContent = "Passwords do not match!";
        confirmText.className = "strength show weak shake";
      } else {
        confirmText.textContent = "Passwords match ✅";
        confirmText.className = "strength show strong";
      }
    });
  </script>

</body>
</html>
