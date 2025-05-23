
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Log In</title>
  <link rel="stylesheet" href="style.css">
  <script>
    function validateForm(event) {

      const username = document.getElementById('form2Example1').value.trim();
      const password = document.getElementById('form2Example2').value.trim();

      if (username === '') {
        alert('Username is required.');
        return false;
      }

      if (password === '') {
        alert('Password is required.');
        return false;
      }
      const successMessage = document.createElement('p');
      successMessage.textContent = 'Form submitted successfully!';
      successMessage.style.color = 'green';
      document.getElementById('loginForm').submit();
    }
    document.addEventListener('DOMContentLoaded', function () {
      const loginForm = document.getElementById('loginForm');
      loginForm.addEventListener('submit', function (event) {
        event.preventDefault();
        validateForm(event);
      });
    });
  </script>
</head>
<body>
  <h2>LOG IN AS STUDENT</h2>
  <form id="loginForm" onsubmit="validateForm(event)">
    <div data-mdb-input-init class="form-outline mb-4">
      <input type="text" id="form2Example1" class="form-control" />
      <label class="form-label" for="form2Example1">Username</label>
    </div>

    <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>
    <div data-mdb-input-init class="form-outline mb-4">
      <input type="password" id="form2Example2" class="form-control" />
      <label class="form-label" for="form2Example2">Password</label>
    </div>

    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>

    <div class="text-center">
      <p>Don't have an account? <a href="register_student.php">Register</a></p>
    </div>
  </form>
</body>
</html>
