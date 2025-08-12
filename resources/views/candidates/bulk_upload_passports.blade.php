<div class="container mt-4">
    <h3>Bulk Upload Candidate Passports</h3>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="passports" class="form-label">Upload ZIP File of Passports</label>
            <input type="file" name="passports" class="form-control" accept=".zip" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
