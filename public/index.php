<?php

require_once ('../config/connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Authorization using JSON Web Tokens and PHP</title>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
  <main class="form-signin">
    <form method="post" action="authenticate.php" id="frmLogin">
      <h1 class="h3 mb-3 fw-normal">Log In</h1>

      <label for="inputEmail" class="visually-hidden">Username</label>
      <input type="text" id="inputEmail" name="username" class="form-control" placeholder="Email address or username" required
        autofocus="">

      <label for="inputPassword" class="visually-hidden">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password">
      
      <p id="invalid" class="text-danger" hidden>Invalid username or password</p>

      <span id="signUp" href="/" style="float:right; color:#0b5ed7">Sign Up</span>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

    </form>

    <form method="post" action="signup.php" id="frmSignUp">
      <h1 class="h3 mb-3 fw-normal">Sign Up</h1>

      <label for="inputName" class="visually-hidden">Name</label>
      <input type="text" id="inputName" name="name" class="form-control" placeholder="Name" required
        autofocus="">

      <label for="inputEmail" class="visually-hidden">Username</label>
      <input type="text" id="inputEmail" name="username" class="form-control" placeholder="Email address or username" required
        autofocus="">

      <label for="inputPassword" class="visually-hidden">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
      
      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

    </form>

    <div id="user_tab" style="display: flex;" hidden>
      <table>
        <tr>
          <?php foreach (get() as $user): ?>
            <td style="border: dashed;padding: 10px">
              <div id="<?= $user['username'] ?>">
                <div class="md-6" style="display: inline">
                  <h3><?= $user['name'] ?></h3>
                </div>
                <div class="md-6" style="display: inline">
                  <h5 class="btn btn-danger" id="delete_<?= $user['username'] ?>" onclick="delete(<?= $user['id'] ?>, <?= $user['role'] ?>)">Delete</h5>
                </div>
            </td>
          <?php endforeach; ?>
        </tr>
      </table>
    </div>
  </main>

  <span id="quit" class="btn btn-warning">Quit</span>

  <script>
    const store = {};
    const loginButton = document.querySelector('#frmLogin');
    const signUpButton = document.querySelector('#frmSignUp');
    const quit = document.querySelector('#quit');
    const signUp = document.querySelector('#signUp');
    const user_tab = document.getElementById('user_tab');
    const formLogin = document.forms[0];
    const formSignup = document.forms[1];

    loginButton.addEventListener('submit', async (e) => {
      e.preventDefault();

      const res = await fetch('/authenticate.php', {
        method: 'POST',
        headers: {
          'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: JSON.stringify({
          username: formLogin.inputEmail.value,
          password: formLogin.inputPassword.value
        })
      });

      if (res.status >= 200 && res.status <= 299) {
        const jwt = await res.text();
        localStorage.setItem('jwt', jwt);
        window.location.reload();
      } else {
        // Handle errors
        document.getElementById('invalid').removeAttribute('hidden');
        console.log(res.status, res.statusText);
      }
    });

    //show action page
    if(localStorage.getItem('jwt')) {
      frmLogin.style.display = 'none';
      user_tab.removeAttribute('hidden');
      quit.style.display = 'block';
    }

    signUpButton.addEventListener('submit', async (e) => {
      e.preventDefault();

      const res = await fetch('/signup.php', {
        method: 'POST',
        headers: {
          'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: JSON.stringify({
          name: formSignup.inputName.value,
          username: formSignup.inputEmail.value,
          password: formSignup.inputPassword.value
        })
      });

      if (res) {
        alert('Register success, please login');
        frmLogin.style.display = 'block';
        frmSignUp.style.display = 'none';
      }
    });

    signUp.addEventListener('click', async (e) => {
      frmLogin.style.display = 'none';
      frmSignUp.style.display = 'block';
    });

    quit.addEventListener('click', async (e) => {
      e.preventDefault();
      localStorage.removeItem('jwt');
      window.location.reload();
    });

    btnGetResource.addEventListener('click', async (e) => {
      const res = await fetch('/resource.php', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
      });
      const timeStamp = await res.text();
      console.log(timeStamp);
    });
  </script>
</body>

</html>
