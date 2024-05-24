<?php
session_start(); 

include("lib/head.php"); 
include("lib/db.php");

if (isset($_GET["del"])) {
    // Retrieve ID from request parameters
    $ContactID = $_GET['ContactID']; 

        $sql_delete = "DELETE FROM contact WHERE ContactID = '$ContactID'";
        if ($db_conn->query($sql_delete) === TRUE) {

            header("Location: contact.php");
            exit();
        } else {
            // Handle errors, if any
            echo "Error deleting category: " . $db_conn->error;
        }
    }

?>
<!doctype html>
<html lang="en">

<head>

    <title>User Contact</title>
</head>
<script type="text/javascript">
    function confirmation() {
        return confirm("Do you want to delete this contact?");
    }
</script>
<body>
    <div class="dashboard-main-wrapper">
        <?php 
            include("lib/navbar.php");
            include("lib/sidebar.php");
        ?>

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">User Contact</h2>

                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">User Contact</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="form-group">
                                <label for="sortBy">Sort By:
                                <select class="form-control" id="sortBy">
                                    <option value="newest" <?php if (!isset($_GET['sortBy']) || $_GET['sortBy'] === 'newest') echo 'selected'; ?>>Newest First</option>
                                    <option value="oldest" <?php if (isset($_GET['sortBy']) && $_GET['sortBy'] === 'oldest') echo 'selected'; ?>>Oldest First</option>
                                </select></label>
                            </div>
                        </div>
                        <div class="card-body" id="contactList">
                            <?php
                                $sortBy = isset($_GET['sortBy']) && $_GET['sortBy'] === 'oldest' ? 'ASC' : 'DESC';
                                $sql = "SELECT * FROM contact ORDER BY ConAddDate $sortBy";
                                $query = $db_conn->query($sql);
                                if ($query) {
                                    while ($row = $query->fetch_assoc()) {
                                        $ContactID = $row["ContactID"];
                                        $contactAddDate = new DateTime($row["ConAddDate"]);
                                        $currentDate = new DateTime();
                                        $diff = $contactAddDate->diff($currentDate);
                                        $daysSinceAdd = $diff->days;
                                        $hoursSinceAdd = $diff->h;
                                        $minutesSinceAdd = $diff->i;
                            ?>  
                            <div class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                               
                                    <h5><a href="contact.php?del=1&ContactID=<?php echo $ContactID ?>" onclick="return confirmation();" style="color: red;padding: 0px;"><i class="m-r-10 mdi mdi-delete-forever"></i></a>
                                    <class="mb-1"><strong style="color:#5c5c5c"><?php echo $row["Subject"]; ?></strong></h5>
                                    <small><?php echo $daysSinceAdd ?> days <?php echo $hoursSinceAdd ?> hours <?php echo $minutesSinceAdd ?> minutes ago</small>

                                </div>
                                <small style="color:#777b7e">User: <?php echo $row["Name"]; ?>  [<a href="mailto:<?php echo $row["Email"]; ?>"><?php echo $row["Email"]; ?></a>]</a></small>
                                <p class="mb-1" style="color:#5c5c5c"><?php echo $row["Message"]; ?></p>
                            </div>
                            <?php
                                    }
                                } else {
                                    echo "Error fetching records: " . $db_conn->error;
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.getElementById('sortBy').addEventListener('change', function() {
    var sortBy = this.value;
    window.location.href = 'contact.php?sortBy=' + sortBy;
});
</script>

</body>
</html>
