<?php
include './header.php';
require_once '../src/Database.php';

// ---------------------- DELETE USER ----------------------
if (isset($_GET['delete'])) {

    if (!ctype_digit($_GET['delete'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    $id = $db->real_escape_string($_GET['delete']);

    // Check if user exists
    $sql = "SELECT id FROM customers WHERE id = '$id'";
    $res = $db->query($sql);

    if ($res->num_rows < 1) {
        header('Location:' . $_SERVER['PHP_SELF']);
        exit();
    }

    $sql = "DELETE FROM customers WHERE id = '$id'";
    if ($db->query($sql)) {
        $msg = "User deleted";
    } else {
        $error = "Cannot delete user";
    }
}

// ---------------------- FETCH CUSTOMERS ----------------------
$sql = "SELECT * FROM customers ORDER BY id DESC";
$res = $db->query($sql);
$customers = [];
while ($row = $res->fetch_object()) {
    $customers[] = $row;
}
?>

<div class="container-fluid">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            <a href="./dashboard.php">Dashboard</a> / Users
        </li>
    </ol>

    <?php if (isset($msg)) : ?>
        <div class="alert alert-success">
            <strong><i class="fa fa-check"></i> Success! </strong> <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif ?>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger">
            <strong><i class="fa fa-times"></i> Failed! </strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif ?>

    <!-- Users Table -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i> User Table
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-sm text-center dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sl. No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th width="70px">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($customers as $customer) : ?>
                            <?php
                            // Determine status color
                            $status = $customer->is_verified;

                            if ($status === "Verified") {
                                $labelColor = "success"; // green
                            } elseif ($status === "Pending") {
                                $labelColor = "warning"; // yellow
                            } else {
                                $labelColor = "danger"; // red (Not Verified)
                            }
                            ?>

                            <tr>
                                <td><?php echo htmlspecialchars($customer->id); ?></td>
                                <td><?php echo htmlspecialchars($customer->name); ?></td>
                                <td><?php echo htmlspecialchars($customer->email); ?></td>
                                <td><?php echo htmlspecialchars($customer->phone); ?></td>

                                <td>
                                    <span class="badge badge-<?php echo $labelColor; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="./view-customer.php?id=<?php echo $customer->id ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?delete=<?php echo $customer->id ?>" 
                                       onclick="return confirm('Are you sure you want to delete this user?')" 
                                       class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>
