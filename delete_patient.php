<?php
include './config/connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $query = "UPDATE patients SET deleted_at = NOW() WHERE id = :id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Patient soft deleted successfully.";
        } else {
            $response['message'] = "Failed to delete patient.";
        }
    } catch (PDOException $ex) {
        $response['message'] = $ex->getMessage();
    }
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
?>
