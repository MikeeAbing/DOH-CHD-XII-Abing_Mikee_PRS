(If xampp v3.3.0 is already installed, and file is already downloaded and extracted to C:\xampp\htdocs, folder name is prs)

STEP 1: Open xampp and start apache and mysql.
STEP 2: Go to http://localhost/phpmyadmin/ and create a new database named prs_db, then import the prs_db.sql file.
STEP 3: Open the prs folder in VSCode.
STEP 4: Go to config folder and open the connection.php file. Update connections on file.
        <?php 
            $host = "localhost";
            $user = "root";
            $password = "";
            $db = "prs_db";
            try {

            $con = new PDO("mysql:dbname=$db;port=3306;host=$host", 
                $user, $password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            } catch(PDOException $e) {
            echo "Connection failed: ".
            $e->getMessage();
            echo $e->getTraceAsString();
            exit;
            }

            session_start();
        ?>
STEP 5: Go to http://localhost/prs/. You will be directed to login page.
STEP 6: Login using these credentials.
            -USERNAME: admin
            -PASSWORD: admin123

(ADDING PATIENTS)
STEP 1: (After Login) Go to "Patients" module, under that click "Add Patients".
STEP 2: On the "Add Patients" page there are two tables: The "Add Patients" table and "Total Patients" table. To add new patient click on the
        "+" icon in the "Add Patients" table.
STEP 3: Fill up the forms, then click "save". Check if the new patient displays at the "Total Patient" table.

(UPDATING & DELETING PATIENT INFORMATION)
In the "Total Patients" table, there is an "Actions" column. On that column, are the edit and delete buttons.

To update patient information:
STEP 1: Click the edit button. You will be directed to "Update Patient Details" page. 
STEP 2: Update input fields you want to change, then click "Save".

To delete patient:
STEP 1: Click the delete button. A confirmation alert will show.
STEP 2: Select "Yes, delete it!" to delete patient.
