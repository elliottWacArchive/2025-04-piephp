<div class="login-container">
    <h1>Connexion</h1>

    <?php if(isset($success) && $success): ?>
    <div class="success-message">
        Inscription réussie ! Vous pouvez maintenant vous connecter.
    </div>
    <?php endif; ?>

    <?php if(isset($error) && $error): ?>
    <div class="error-message">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <form action="/PiePHP/user/login" method="POST">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" required
                value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-actions">
            <button type="submit">Se connecter</button>
        </div>
    </form>

    <div class="register-link">
        Pas encore de compte ? <a href="/PiePHP/user/register">S'inscrire</a>
    </div>
</div>

<style>
.login-container {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;