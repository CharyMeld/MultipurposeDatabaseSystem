require_once __DIR__ . '/../config/database.php';

$pdo = getPDOConnection();

$faculty = $_GET['faculty'] ?? '';
$entry_mode = $_GET['entry_mode'] ?? '';

$sql = "SELECT c.*, f.name AS faculty_name FROM candidates c
        LEFT JOIN faculties f ON c.faculty_id = f.id
        WHERE 1=1";

$params = [];
if (!empty($faculty)) {
    $sql .= " AND c.faculty_id = :faculty";
    $params[':faculty'] = $faculty;
}
if (!empty($entry_mode)) {
    $sql .= " AND c.entry_mode = :entry_mode";
    $params[':entry_mode'] = $entry_mode;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$candidates = $stmt->fetchAll();

// Return JSON if needed
header('Content-Type: application/json');
echo json_encode($candidates);

