<h2>Edit Role</h2>
<form method="POST" action="">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($role['name']) ?>" required><br>
    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($role['description']) ?></textarea><br>
    <button type="submit">Update Role</button>
</form>

