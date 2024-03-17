<header>
    <div class="banner">
        <img src="../../images/Tekken8Banner.jpg" alt="Banner alt text">
    </div>
    <div class="navBar">
        <nav>
            <ul>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="Index.php">Home</a></li>
                    <li><a href="Login.php">Login</a></li>
                    <li><a href="Register.php">Register</a></li>
                <?php else: ?>
                    <li><a href="../Proc/LogoutProc.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
