<!DOCTYPE html>
<html>
<head>
    <title>Debug Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4>🧪 Debug Information</h4>
                    </div>
                    <div class="card-body">
                        <h5>✅ Success! You reached the View.php page</h5>
                        
                        <div class="alert alert-success">
                            <strong>Login Process:</strong><br>
                            1. ✅ Login form submitted successfully<br>
                            2. ✅ AuthController processed login<br>
                                 Redirecting to UserController::show()<br>
                            4. ✅ This debug page is showing you made it!
                        </div>

                        <h6>Environment Variables Test:</h6>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>DB_HOST:</strong> <?= getenv('DB_HOST') ?: 'Not set (using fallback)' ?>
                            </li>
                            <li class="list-group-item">
                                <strong>DB_PORT:</strong> <?= getenv('DB_PORT') ?: 'Not set (using fallback)' ?>
                            </li>
                            <li class="list-group-item">
                                <strong>DB_USER:</strong> <?= getenv('DB_USER') ?: 'Not set (using fallback)' ?>
                            </li>
                        </ul>

                        <div class="mt-4">
                            <a href="<?= site_url('author') ?>" class="btn btn-primary">
                                🔄 Try Real View.php
                            </a>
                            <a href="<?= site_url('auth/login') ?>" class="btn btn-secondary">
                                🏠 Back to Login
                            </a>
                        </div>

                        <div class="mt-4 alert alert-warning">
                            <strong>Next Steps:</strong><br>
                            1. Set up a real database on Render<br>
                            2. Add proper environment variables<br>
                            3. Import your data or create test users
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
