<?php 
include('header.php'); 
include('hover.php'); 
include('admin/dbcon.php'); 

if (isset($_POST['save'])) {
    $CYS = trim($_POST['CYS']);
    $semester = trim($_POST['semester']);
    $sy = trim($_POST['sy']);

    // طباعة القيم المستلمة للتحقق
    echo "<pre>Received Data: CYS=$CYS, Semester=$semester, SY=$sy</pre>";

    // استعلام SQL لاسترجاع الجدول بناءً على القيم المدخلة
    $query = "SELECT * FROM schedule WHERE CYS = '$CYS' AND semester = '$semester' AND sy = '$sy'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    echo "<div class='container'>";
    echo "<h2 class='mt-4'>Schedule Preview for Course Year Section</h2>";

    if (mysqli_num_rows($result) > 0) {
        // زر الطباعة
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
                    <td>{$row['day']}</td>
                    <td>{$row['time']}</td>
                    <td>{$row['time_end']}</td>
                    <td>{$row['semester']}</td>
                    <td>{$row['sy']}</td>
                    <td>{$row['CYS']}</td>
                    <td>{$row['subject']}</td>
                    <td>{$row['teacher']}</td>
                    <td>{$row['room']}</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-warning'>No records found for the given criteria.</div>";
    }

    echo "<a href='schedule.php' class='btn btn-primary'>Back</a>";
    echo "</div>";
}
?>

<script>
function printSchedule() {
    var printContents = document.getElementById("scheduleTable").outerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = "<html><head><title>Print Schedule</title></head><body>" + printContents + "</body></html>";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
