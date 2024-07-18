<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uas-pa-sigit"; // Ganti dengan nama database Anda

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tambah atau edit pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $namaLengkap = $_POST['namaLengkap'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $banned = $_POST['banned'];
    $akses = 0;
    if (isset($_POST['akses_admin'])) $akses += 1;
    if (isset($_POST['akses_operator'])) $akses += 2;

    if (empty($id)) {
        // Tambah pengguna baru
        $sql = "INSERT INTO users (username, password, namaLengkap, email, telepon, banned, akses)
                VALUES ('$username', '$password', '$namaLengkap', '$email', '$telepon', '$banned', '$akses')";
    } else {
        // Edit pengguna
        $sql = "UPDATE users SET username='$username', password='$password', namaLengkap='$namaLengkap', email='$email',
                telepon='$telepon', banned='$banned', akses='$akses' WHERE id=$id";
    }
    $conn->query($sql);
}

// Hapus pengguna
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

// Ambil data pengguna
$result = $conn->query("SELECT * FROM users");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manajemen Pengguna</h2>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#userModal" onclick="resetForm()">+ Add</button>
    <table class="table table-bordered ">
        <thead style="background-color: aqua;">
        <tr>
                <th>No</th>
                <th>ID</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>telepon</th>
                <th>Banned</th>
                <th>Waktu Login</th>
                <th>Akses</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>$no</td>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['namaLengkap']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['telepon']}</td>
                        <td>" . ($row['banned'] ? 'Y' : 'N') . "</td>
                        <td>{$row['login_time']}</td>
                        <td>{$row['akses']}</td>
                        <td>
                            <button class='btn btn-info' onclick='editUser(" . json_encode($row) . ")'>Edit</button>


                               <a href='?delete={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus pengguna ini?\")'>Delete</a>
                        </td>
                    </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah/Edit Pengguna -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">

<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Input Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="namaLengkap">Full Name</label>
                        <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>


                        </div>
                    <div class="form-group">
                        <label for="telepon">telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                    </div>
                    <div class="form-group">
                        <label>Banned:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="banned" id="bannedYes" value="1" required>
                            <label class="form-check-label" for="bannedYes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="banned" id="bannedNo" value="0" required>
                            <label class="form-check-label" for="bannedNo">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Akses:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aksesAdmin" name="akses_admin">
                            <label class="form-check-label" for="aksesAdmin">Administrator</label>

                            </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aksesOperator" name="akses_operator">
                            <label class="form-check-label" for="aksesOperator">Operator</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editUser(user) {
        $('#id').val(user.id);
        $('#username').val(user.username);
        $('#password').val(''); // Kosongkan password
        $('#namaLengkap').val(user.namaLengkap);
        $('#email').val(user.email);
        $('#telepon').val(user.telepon);
        if (user.banned) {
            $('#bannedYes').prop('checked', true);
        } else {
            $('#bannedNo').prop('checked', true);
        }
        $('#aksesAdmin').prop('checked', user.akses & 1);
        $('#aksesOperator').prop('checked', user.akses & 2);
        $('#userModal').modal('show');
    }

    function resetForm() {
        $('#userForm')[0].reset();
        $('#id').val('');
        $('#aksesAdmin').prop('checked', false);
        $('#aksesOperator').prop('checked', false);
    }
</script>
</body>
</html>