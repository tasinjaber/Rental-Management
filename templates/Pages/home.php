<?php
/**
 * Home page template
 * This is a simple redirect page - users will be redirected to dashboard or landing
 */
$this->layout = 'default';
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Welcome to Rental Management System</h1>
            <p class="lead">Please <a href="/login">login</a> to access your dashboard.</p>
            <p>Or visit our <a href="/rental-management">landing page</a>.</p>
        </div>
    </div>
</div>

