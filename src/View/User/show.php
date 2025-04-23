<div class="profile-container">
    <h1>Mon Profil</h1>

    <?php if(isset($error) && $error): ?>
    <div class="error-message">
        <?php echo $error; ?>
    </div>
    <?php else: ?>
    <div class="profile-info">
        <div class="info-group">
            <label>Email :</label>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="info-group">
            <label>Date d'inscription :</label>
            <span><?php echo isset($user['created_at']) ? htmlspecialchars($user['created_at']) : 'Non disponible'; ?></span>
        </div>
    </div>

    <div class="actions">
        <a href="/PiePHP/user/logout" class="logout-button">Déconnexion</a>
    </div>
    <?php endif; ?>
</div>

<style>
.profile-container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    margin-bottom: 20px;
    color: #333;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.profile-info {
    margin-bottom: 30px;
}

.info-group {
    margin-bottom: 15px;
    display: flex;
}

.info-group label {
    font-weight: bold;
    min-width: 150px;
}

.info-group span {
    color: #555;
}

.actions {
    margin-top: 30px;
    text-align: center;
}

.logout-button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.logout-button:hover {
    background-color: #c82333;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
}
</style>