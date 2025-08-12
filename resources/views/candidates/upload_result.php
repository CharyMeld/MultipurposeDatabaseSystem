<h2>Upload Summary</h2>

<?php if ($preview): ?>
    <h3>✅ Successfully Uploaded (<?= count($success) ?>)</h3>
    <table border="1">
        <tr>
            <?php foreach (array_keys($preview[0]) as $head): ?>
                <th><?= htmlspecialchars($head) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($preview as $row): ?>
            <tr>
                <?php foreach ($row as $val): ?>
                    <td><?= htmlspecialchars($val) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if ($errors): ?>
    <h3>❌ Errors (<?= count($errors) ?>)</h3>
    <ul>
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

