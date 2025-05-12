<?php 
include('header.php'); 
include('hover.php'); 
include('admin/dbcon.php'); 

// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

// التحقق من أن الاتصال بقاعدة البيانات ناجح
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_room'])) {
    $room_name = trim($_POST['room_name']);

    if (!empty($room_name)) {
        // استعلام SQL لاسترجاع الجدول بناءً على اسم القاعة
        $query = "SELECT * FROM schedule WHERE room = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $room_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        echo "<div class='container'>";
        echo "<h2 class='mt-4'>Schedule for Room: <strong>" . htmlspecialchars($room_name) . "</strong></h2>";

        if (mysqli_num_rows($result) > 0) {
            echo "<button class='btn btn-success mb-3' onclick='printSchedule()'>Print Schedule</button>";
            echo "<table id='scheduleTable' class='table table-bordered table-striped'>";
            echo "<thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th>Course/Year</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Room</th>
                    </tr>
                  </thead>
                  <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['day'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['time'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['time_end'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['semester'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['sy'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['CYS'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['subject'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['teacher'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($row['room'] ?? 'N/A') . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>No schedule found for the given room.</div>";
        }
        echo "<a href='search_room.php' class='btn btn-primary'>Back</a>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>Please enter a valid room name.</div>";
    }
}
?>

<script>
function printSchedule() {
    var printWindow = window.open('', '', 'width=800,height=600');
    var printContents = document.getElementById("scheduleTable").outerHTML;
    printWindow.document.write("<html><head><title>Print Schedule</title></head><body>" + printContents + "</body></html>");
    printWindow.document.close();
    printWindow.print();
}
</script>