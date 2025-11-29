<?php
session_start();

$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $err = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email.";
    } else {
        // MOCK: do NOT check database or send email
        // Just notify the user

        $_SESSION['reset_message'] = "If an account with that email exists, a reset link has been sent.";

        // Redirect back to login page after 1 second
        header("refresh:1; url=login.php");
        $success = $_SESSION['reset_message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container" style="padding-top:120px; padding-bottom:100px;">
    <div class="col-lg-4 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 text-center">Forgot Password</h4>

                <?php if ($err): ?>
                    <div class="alert alert-danger"><?php echo $err; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $success; ?><br>
                        Returning to login...
                    </div>
                <?php else: ?>

                <form method="post" action="">
                    <div class="form-group">
                        <label for="email">Enter your email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="you@example.com" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Send Reset Link
                    </button>

                    <a href="login.php" class="btn btn-link btn-block">Back to Login</a>
                </form>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

</body>
</html>
