<style>
    .faculty-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add {
        padding: 8px 12px;
        background: #2f4f4f;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .faculty-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .faculty-form input,
    .faculty-form textarea,
    .faculty-form select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .faculty-form button {
        grid-column: span 2;
        padding: 10px;
        background-color: #2f4f4f;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    .action-btn {
        background-color: #1f2937;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        position: relative;
    }

    .action-menu {
        display: none;
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        border-radius: 5px;
        right: 0;
        top: 30px;
        z-index: 10;
        min-width: 100px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .action-menu button {
        display: block;
        background: none;
        border: none;
        padding: 10px;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .page-container {
        margin-left: 260px; /* Match the sidebar width */
        padding: 30px 20px;
        background-color: #f8fafc;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    /* Responsive for small screens */
    @media (max-width: 768px) {
        .page-container {
            margin-left: 0;
            padding: 20px 10px;
        }
    }


    .action-menu button:hover {
        background-color: #f0f0f0;
    }
</style>

<script>
    function toggleActionMenu(id) {
        const menu = document.getElementById('action-menu-' + id);
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';

        // Hide other menus
        document.querySelectorAll('.action-menu').forEach(el => {
            if (el.id !== 'action-menu-' + id) el.style.display = 'none';
        });
    }

    // Optional: Hide menus if clicked outside
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('action-btn')) {
            document.querySelectorAll('.action-menu').forEach(el => el.style.display = 'none');
        }
    });
</script>

<div class="page-container">

    <div class="faculty-header">
        <h2>🎓 Faculties</h2>
        <button class="btn-add" onclick="document.getElementById('addForm').style.display='grid'">➕ Add Faculty</button>
    </div>

    <!-- Add Faculty Form -->
    <form id="addForm" class="faculty-form" method="post" style="display:<?= isset($facultyToEdit) ? 'grid' : 'none' ?>;">
        <input type="hidden" name="faculty_id" value="<?= $facultyToEdit['id'] ?? '' ?>">
        <input type="text" name="name" placeholder="Faculty Name" value="<?= $facultyToEdit['name'] ?? '' ?>" required>
        <input type="text" name="short_code" placeholder="Short Code" value="<?= $facultyToEdit['short_code'] ?? '' ?>" required>
        <textarea name="description" placeholder="Description" required><?= $facultyToEdit['description'] ?? '' ?></textarea>
        <select name="status">
            <option value="active" <?= (isset($facultyToEdit) && $facultyToEdit['status'] == 1) ? 'selected' : '' ?>>Active</option>
            <option value="disabled" <?= (isset($facultyToEdit) && $facultyToEdit['status'] == 0) ? 'selected' : '' ?>>Disabled</option>
        </select>
        <button type="submit" name="<?= isset($facultyToEdit) ? 'update_faculty' : 'add_faculty' ?>">
            <?= isset($facultyToEdit) ? 'Update Faculty' : 'Save Faculty' ?>
        </button>
    </form>

    <!-- Faculties Table -->
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Short Code</th>
                <th>Description</th>
                <th>Status</th>
                <th>Time Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($faculties) > 0): ?>
                <?php foreach ($faculties as $index => $faculty): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($faculty['name']) ?></td>
                        <td><?= htmlspecialchars($faculty['short_code']) ?></td>
                        <td><?= htmlspecialchars($faculty['description']) ?></td>
                        <td><?= $faculty['status'] == 1 ? 'Active' : 'Disabled' ?></td>
                        <td><?= htmlspecialchars($faculty['time_created']) ?></td>
                        <td style="position: relative;">
                            <button class="action-btn" onclick="event.stopPropagation(); toggleActionMenu(<?= $faculty['id'] ?>)">Actions ⌄</button>
                            <div class="action-menu" id="action-menu-<?= $faculty['id'] ?>">
                                <a href="?url=faculty&action=edit&id=<?= $faculty['id'] ?>"><button>Edit</button></a>
                                <a href="?url=faculty&action=delete&id=<?= $faculty['id'] ?>" onclick="return confirm('Are you sure you want to delete this faculty?')"><button>Delete</button></a>
                                <a href="?url=faculty&action=toggle&id=<?= $faculty['id'] ?>"><button>
                                    <?= $faculty['status'] == 1 ? 'Disable' : 'Enable' ?>
                                </button></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No faculties found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div> <!-- End of .page-container -->
