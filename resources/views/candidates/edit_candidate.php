<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Candidate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        /* Layout handling for sidebar */
        .container {
            max-width: 95%;
            margin: 50px auto;
            padding: 20px;
            margin-left: 270px; /* adjust based on your sidebar width */
            background: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        form.form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-actions {
            grid-column: 1 / -1;
            text-align: right;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #0066cc;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #004d99;
        }
    </style>
</head>
<body>


    <div class="container">
        <h2>Edit Candidate</h2>

        <form action="/wacs_system/public/?url=update-candidate&id=<?= $candidate['id'] ?>" method="post" class="form-grid">

            <div class="form-group">
                <label>Registration Number:</label>
                <input type="text" name="registration_number" value="<?= $candidate['registration_number'] ?>" required>
            </div>

            <div class="form-group">
                <label>Certificate Number:</label>
                <input type="text" name="cert_number" value="<?= $candidate['cert_number'] ?>">
            </div>

            <div class="form-group">
                <label>Surname:</label>
                <input type="text" name="surname" value="<?= $candidate['surname'] ?>" required>
            </div>

            <div class="form-group">
                <label>Other Name:</label>
                <input type="text" name="other_name" value="<?= $candidate['other_name'] ?>">
            </div>

            <div class="form-group">
                <label>Maiden Name:</label>
                <input type="text" name="maiden_name" value="<?= $candidate['maiden_name'] ?>">
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= $candidate['email'] ?>">
            </div>

            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?= $candidate['phone'] ?>">
            </div>

            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" value="<?= $candidate['address'] ?>">
            </div>

            <div class="form-group">
                <label>Postal Address:</label>
                <input type="text" name="postal_address" value="<?= $candidate['postal_address'] ?>">
            </div>

            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="dob" value="<?= $candidate['dob'] ?>">
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <select name="gender">
                    <option value="Male" <?= $candidate['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $candidate['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nationality:</label>
                <input type="text" name="nationality" value="<?= $candidate['nationality'] ?>">
            </div>

            <div class="form-group">
                <label>Country:</label>
                <input type="text" name="country" value="<?= $candidate['country'] ?>">
            </div>

            <div class="form-group">
                <label>Faculty:</label>
                <select name="faculty_id">
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?= $faculty['id'] ?>" <?= $candidate['faculty_id'] == $faculty['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($faculty['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Sub Speciality:</label>
                <input type="text" name="sub_speciality" value="<?= $candidate['sub_speciality'] ?>">
            </div>

            <div class="form-group">
                <label>Full Registration Date:</label>
                <input type="date" name="full_registration_date" value="<?= $candidate['full_registration_date'] ?>">
            </div>

            <div class="form-group">
                <label>Entry Mode:</label>
                <input type="text" name="entry_mode" value="<?= $candidate['entry_mode'] ?>">
            </div>

            <div class="form-group">
                <label>NYSC Discharge/Exemption:</label>
                <input type="text" name="nysc_discharge_or_exemption" value="<?= $candidate['nysc_discharge_or_exemption'] ?>">
            </div>

            <div class="form-group">
                <label>Accredited Training Program:</label>
                <input type="text" name="accredited_training_program" value="<?= $candidate['accredited_training_program'] ?>">
            </div>

            <div class="form-group">
                <label>Post-Registration Appointment:</label>
                <input type="text" name="post_registration_appointment" value="<?= $candidate['post_registration_appointment'] ?>">
            </div>

            <div class="form-actions">
                <button type="submit">Update Candidate</button>
            </div>

        </form>
    </div>

</body>
</html>
