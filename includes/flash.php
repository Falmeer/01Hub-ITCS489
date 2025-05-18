<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-<?=$_SESSION['flash']['type']?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
