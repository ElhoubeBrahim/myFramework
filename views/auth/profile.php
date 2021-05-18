<main class="profile-page">
  <?php if (isset($user)): ?>

        <div class="welcome">
            Welcome <span class="user"><?= $user['name'] ?? 'anonymous' ?></span>
        </div>

	<?php endif; ?>
</main>