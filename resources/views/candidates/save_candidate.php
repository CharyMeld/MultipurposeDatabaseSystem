<?php
require_once __DIR__ . '/../../../config/database.php';

try {
    // Connect to the database
    $pdo = getPDOConnection();

    // Prepare the SQL INSERT query
    $stmt = $pdo->prepare("
        INSERT INTO candidates (
            registration_number, cert_number, surname, other_name, maiden_name, 
            entry_mode, country, dob, gender, nationality, 
            sub_speciality, faculty_id, nysc_discharge_or_exemption, 
            accredited_training_program, full_registration_date, created_at
        ) VALUES (
            :registration_number, :cert_number, :surname, :other_name, :maiden_name, 
            :entry_mode, :country, :dob, :gender, :nationality, 
            :sub_speciality, :faculty_id, :nysc_discharge_or_exemption, 
            :accredited_training_program, :full_registration_date, NOW()
        )
    ");

    // Bind form data
    $stmt->execute([
        ':registration_number' => $_POST['registration_number'] ?? '',
        ':cert_number' => $_POST['cert_number'] ?? '',
        ':surname' => $_POST['surname'] ?? '',
        ':other_name' => $_POST['other_name'] ?? '',
        ':maiden_name' => $_POST['maiden_name'] ?? '',
        ':entry_mode' => $_POST['entry_mode'] ?? '',
        ':country' => $_POST['country'] ?? '',
        ':dob' => $_POST['dob'] ?? null,
        ':gender' => $_POST['gender'] ?? '',
        ':nationality' => $_POST['nationality'] ?? '',
        ':sub_speciality' => $_POST['sub_speciality'] ?? '',
        ':faculty_id' => $_POST['faculty_id'] ?? null,
        ':nysc_discharge_or_exemption' => $_POST['nysc_discharge_or_exemption'] ?? '',
        ':accredited_training_program' => $_POST['accredited_training_program'] ?? '',
        ':full_registration_date' => $_POST['full_registration_date'] ?? null,
    ]);

    // Redirect to candidate list or success page
    header('Location: ../views/candidates/index.php?success=1');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

