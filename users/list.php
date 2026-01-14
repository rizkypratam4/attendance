<?php
require __DIR__ . "/../config/database.php";

$result = $conn->query("SELECT id, name, username FROM users ORDER BY id DESC");

$no = 1;
while ($row = $result->fetch_assoc()):
?>
<tr>
    <td scope="col" class="col-no"><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= htmlspecialchars($row['username']); ?></td>
    <td>
        <button
        class="btn btn-sm btn-info btn-edit-user"
        data-id="<?= $row['id']; ?>"
        data-name="<?= htmlspecialchars($row['name']); ?>"
        data-username="<?= htmlspecialchars($row['username']); ?>"
        title="Edit User"
        >
            <i class="ti ti-pencil"></i>
            Edit
        </button>

        <button
            class="btn btn-sm btn-danger btn-delete-user ms-1"
            data-id="<?= $row['id']; ?>"    
            data-name="<?= $row['name']; ?>"    
            title="Hapus User"
        >
            <i class="ti ti-trash"></i>
            Delete
        </button>
    </td>
</tr>
<?php endwhile; ?>
