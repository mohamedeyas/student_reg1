<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$username = "root";
$password = "";
$database = "php_student_reg";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failure: " . $conn->connect_error);
}

$search = "";

if (isset($_GET['search']) && $_GET['search'] != "") {

    $search = $conn->real_escape_string($_GET['search']);

    $result = $conn->query("
        SELECT * FROM students
        WHERE
            CONCAT('STU', LPAD(id,4,'0')) LIKE '%$search%'
            OR name LIKE '%$search%'
            OR email LIKE '%$search%'
            OR department LIKE '%$search%'
        ORDER BY id DESC
    ");

} else {

    $result = $conn->query("
        SELECT * FROM students
        ORDER BY id DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    color:#1e293b;
}

/* NAVBAR */
.navbar-custom{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    padding:18px 30px;
    box-shadow:0 10px 25px rgba(0,0,0,.12);
}

.navbar-brand{
    color:#fff !important;
    font-size:26px;
    font-weight:700;
    letter-spacing:.5px;
}

/* CARD */
.card-custom{
    background:#fff;
    border:none;
    border-radius:20px;
    padding:28px;
    box-shadow:0 15px 35px rgba(0,0,0,.08);
    transition:.4s;
}

.card-custom:hover{
    transform:translateY(-5px);
    box-shadow:0 20px 40px rgba(0,0,0,.12);
}

/* SECTION TITLE */
h4{
    font-weight:700;
    color:#0f172a;
}

/* LABEL */
.form-label{
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    color:#64748b;
    margin-bottom:6px;
}

/* INPUT */
.form-control-custom{
    border:2px solid #e2e8f0;
    border-radius:12px;
    padding:12px 15px;
    font-size:14px;
    transition:.3s;
    background:#fff;
}

.form-control-custom:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.15);
    outline:none;
}

/* SUBJECT BADGE */
.badge-sub{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    color:white;
    font-size:11px;
    font-weight:700;
    border-radius:8px;
    padding:6px;
}

/* SAVE BUTTON */
.btn-primary-custom{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    border:none;
    color:white;
    font-weight:600;
    border-radius:12px;
    padding:14px;
    transition:.3s;
}

.btn-primary-custom:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,.30);
}

/* SEARCH */
.search-box{
    border-radius:12px;
    border:2px solid #e2e8f0;
    height:45px;
}

.btn-search{
    background:linear-gradient(135deg,#10b981,#059669);
    color:white;
    border:none;
    border-radius:12px;
    padding:0 20px;
    font-weight:600;
}

.btn-search:hover{
    box-shadow:0 8px 18px rgba(16,185,129,.35);
}

/* TABLE */
.table-custom{
    overflow:hidden;
    border-radius:15px;
}

.table-custom thead th{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    color:white;
    border:none;
    text-align:center;
    padding:15px;
    font-size:12px;
    font-weight:700;
}

.table-custom tbody tr{
    transition:.3s;
}

.table-custom tbody tr:hover{
    background:#eff6ff;
    transform:scale(1.002);
}

.table-custom td{
    vertical-align:middle;
    padding:14px;
}

/* STUDENT NAME */
.student-name{
    font-weight:700;
    color:#0f172a;
}

.student-email{
    color:#64748b;
    font-size:12px;
}

/* ROLL NUMBER */
.roll-badge{
    background:linear-gradient(135deg,#f59e0b,#f97316);
    color:white;
    padding:7px 14px;
    border-radius:30px;
    font-weight:700;
    font-size:12px;
}

/* AGE */
.age-badge{
    background:#dbeafe;
    color:#1d4ed8;
    padding:6px 12px;
    border-radius:30px;
    font-weight:700;
}

/* TOTAL */
.m-total{
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    color:white;
    padding:7px 14px;
    border-radius:30px;
    font-weight:700;
}

/* AVERAGE */
.m-avg{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    padding:7px 14px;
    border-radius:30px;
    font-weight:700;
}

/* SUCCESS ALERT */
.alert-success{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    border:none;
    font-weight:600;
}

/* SCROLLBAR */
::-webkit-scrollbar{
    width:8px;
}

::-webkit-scrollbar-thumb{
    background:#2563eb;
    border-radius:20px;
}

/* MOBILE */
@media(max-width:768px){

    .navbar-brand{
        font-size:20px;
    }

    .card-custom{
        padding:18px;
    }

    .table{
        font-size:12px;
    }
}

</style>
</head>
<body>

<nav class="navbar navbar-custom py-3 px-4 shadow-sm">
    <span class="navbar-brand mb-0 h1 font-weight-bold text-primary" style="color: #000000 !important;">⚡️ Student Registration Form</span>
</nav>

<?php if (isset($_GET['status']) && $_GET['status'] === "success"): ?>
    <div class="alert alert-success border-0 rounded-0 text-center py-3 m-0 font-weight-bold" role="alert">
        🎉 Student registered successfully! Memory buffers cleared.
    </div>
<?php endif; ?>

<div class="container-fluid my-4 px-4">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card-custom">
                <h4 class="font-weight-bold mb-4" style="letter-spacing: -0.5px;">Add New Record</h4>
                <form action="" method="post">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="Enther the name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-custom" placeholder="jane@example.com" required>
                    </div>
                    <div class="form-row">
    <div class="form-group col-6">
        <label class="form-label">Phone</label>
        <input type="tel" name="phone" class="form-control form-control-custom" placeholder="1234567890" required>
    </div>

    <div class="form-group col-6">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-control form-control-custom" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-6">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="dob" id="dob" class="form-control form-control-custom" required>
    </div>

    <div class="form-group col-6">
        <label class="form-label">Age</label>
        <input type="text" name="age" id="age" class="form-control form-control-custom" readonly>
    </div>
</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control form-control-custom" rows="2" placeholder="Enter the Address"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control form-control-custom" placeholder="Information Tech" required>
                        </div>
                        <div class="form-group col-5">
                            <label class="form-label">Joined Date</label>
                            <input type="date" name="joined_date" class="form-control form-control-custom" required>
                        </div>
                    </div>
                    <div class="form-group form-check my-3 pl-4">
                        <input type="checkbox" name="parttime" class="form-check-input" id="pt">
                        <label class="form-check-label text-muted font-weight-medium" for="pt" style="font-size: 13px; cursor:pointer;">Part-Time Student Enrolment</label>
                    </div>

                    <div class="mt-4">
                        <label class="form-label text-primary">Subject Scores (0-100)</label>
                        <div class="form-row text-center">
                            <div class="form-group col"><div class="badge-sub mb-1">S1</div><input type="number" name="subject1" class="form-control form-control-custom text-center px-1" min="0" max="100" required></div>
                            <div class="form-group col"><div class="badge-sub mb-1">S2</div><input type="number" name="subject2" class="form-control form-control-custom text-center px-1" min="0" max="100" required></div>
                            <div class="form-group col"><div class="badge-sub mb-1">S3</div><input type="number" name="subject3" class="form-control form-control-custom text-center px-1" min="0" max="100" required></div>
                            <div class="form-group col"><div class="badge-sub mb-1">S4</div><input type="number" name="subject4" class="form-control form-control-custom text-center px-1" min="0" max="100" required></div>
                            <div class="form-group col"><div class="badge-sub mb-1">S5</div><input type="number" name="subject5" class="form-control form-control-custom text-center px-1" min="0" max="100" required></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-custom btn-block mt-3 shadow-sm">Save Student Record</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card-custom">
                <h4 class="font-weight-bold mb-4" style="letter-spacing: -0.5px;">
    Active Student Directory
</h4>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search Roll No, Name, Email, Department"
            value="<?= htmlspecialchars($search) ?>"
        >

        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                Search
            </button>

            <a href="<?= $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </div>
</form>
                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-custom m-0" style="font-size: 13.5px;">
                            <thead>
<tr>
    <th>Enroll No</th>
    <th>Student Profiles</th>
    <th>Department</th>
    <th class="text-center">Age</th>
    <th class="text-center">S1</th>
    <th class="text-center">S2</th>
    <th class="text-center">S3</th>
    <th class="text-center">S4</th>
    <th class="text-center">S5</th>
    <th class="text-center">Total</th>
    <th class="text-center">Average</th>
</tr>
</thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): 
                                    $total = $row['subject1'] + $row['subject2'] + $row['subject3'] + $row['subject4'] + $row['subject5'];
                                    $average = number_format(($total / 5), 1);
                                ?>
                                   <tr>
    <td>
        STU<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>
    </td>

    <td>
        <div class="font-weight-bold text-dark">
            <?= htmlspecialchars($row['name']) ?>
        </div>
        <div class="text-muted" style="font-size:11px;">
            <?= htmlspecialchars($row['email']) ?>
        </div>
    </td>

    <td><?= htmlspecialchars($row['department']) ?></td>

    <td class="text-center">
        <?= $row['age'] ?>
    </td>

    <td class="text-center"><?= $row['subject1'] ?></td>
    <td class="text-center"><?= $row['subject2'] ?></td>
    <td class="text-center"><?= $row['subject3'] ?></td>
    <td class="text-center"><?= $row['subject4'] ?></td>
    <td class="text-center"><?= $row['subject5'] ?></td>

    <td class="text-center">
        <span class="metric-badge m-total"><?= $total ?></span>
    </td>

    <td class="text-center">
        <span class="metric-badge m-avg"><?= $average ?>%</span>
    </td>
</tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <p class="text-muted font-weight-medium m-0">No active student listings found inside the registry.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('dob').addEventListener('change', function () {

    let dob = new Date(this.value);
    let today = new Date();

    let age = today.getFullYear() - dob.getFullYear();

    let monthDiff = today.getMonth() - dob.getMonth();

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < dob.getDate())
    ) {
        age--;
    }

    document.getElementById('age').value = age;
});
</script>
<?php if ($conn) { $conn->close(); } ?>
</body>
</html>