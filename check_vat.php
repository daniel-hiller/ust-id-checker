<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($data['ustId1']) || empty($data['ustId2'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Build API URL
$apiUrl = 'https://evatr.bff-online.de/evatrRPC';
$params = [
    'UstId_1' => $data['ustId1'],
    'UstId_2' => $data['ustId2']
];

// Add optional fields if provided
if (!empty($data['firmenname'])) $params['Firmenname'] = $data['firmenname'];
if (!empty($data['ort'])) $params['Ort'] = $data['ort'];
if (!empty($data['plz'])) $params['PLZ'] = $data['plz'];
if (!empty($data['strasse'])) $params['Strasse'] = $data['strasse'];

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

// Execute cURL request
$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'API request failed: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

// Get HTTP status code
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Parse XML response
$xml = simplexml_load_string($response);
if ($xml === false) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to parse API response',
        'raw_response' => $response,
        'http_code' => $httpCode
    ]);
    exit;
}

// Convert XML-RPC response to array
$result = [];
foreach ($xml->param as $param) {
    $data = $param->value->array->data;
    $key = (string)$data->value[0]->string;
    $value = (string)$data->value[1]->string;
    $result[$key] = $value;
}

// Build response
$response = [
    'errorCode' => $result['ErrorCode'] ?? '',
    'errorMessage' => $result['ErrorMessage'] ?? '',
    'valid' => ($result['ErrorCode'] ?? '') === '200',
    'details' => [
        'ustId1' => $result['UstId_1'] ?? '',
        'ustId2' => $result['UstId_2'] ?? '',
        'firmenname' => $result['Firmenname'] ?? '',
        'ort' => $result['Ort'] ?? '',
        'plz' => $result['PLZ'] ?? '',
        'strasse' => $result['Strasse'] ?? '',
        'gueltigAb' => $result['Gueltig_ab'] ?? '',
        'gueltigBis' => $result['Gueltig_bis'] ?? '',
        'druck' => $result['Druck'] ?? '',
        'datum' => $result['Datum'] ?? '',
        'uhrzeit' => $result['Uhrzeit'] ?? '',
        'ergName' => $result['Erg_Name'] ?? '',
        'ergOrt' => $result['Erg_Ort'] ?? '',
        'ergPlz' => $result['Erg_PLZ'] ?? '',
        'ergStr' => $result['Erg_Str'] ?? ''
    ]
];

// Add additional information based on error code
switch ($response['errorCode']) {
    case '200':
        $response['message'] = 'Die angefragte USt-IdNr. ist gültig.';
        break;
    case '201':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig.';
        break;
    case '202':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie ist nicht in der Unternehmerdatei des betreffenden EU-Mitgliedstaates registriert.';
        break;
    case '203':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie ist erst ab dem ' . $response['details']['gueltigAb'] . ' gültig.';
        break;
    case '204':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie war im Zeitraum von ' . $response['details']['gueltigAb'] . ' bis ' . $response['details']['gueltigBis'] . ' gültig.';
        break;
    case '205':
        $response['message'] = 'Ihre Anfrage kann derzeit durch den angefragten EU-Mitgliedstaat oder aus anderen Gründen nicht beantwortet werden. Bitte versuchen Sie es später noch einmal.';
        break;
    case '206':
        $response['message'] = 'Ihre deutsche USt-IdNr. ist ungültig. Eine Bestätigungsanfrage ist daher nicht möglich.';
        break;
    case '208':
        $response['message'] = 'Für die von Ihnen angefragte USt-IdNr. läuft gerade eine Anfrage von einem anderen Nutzer. Bitte versuchen Sie es später noch einmal.';
        break;
    case '209':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie entspricht nicht dem Aufbau der für diesen EU-Mitgliedstaat gilt.';
        break;
    case '210':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie entspricht nicht den Prüfziffernregeln die für diesen EU-Mitgliedstaat gelten.';
        break;
    case '211':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie enthält unzulässige Zeichen.';
        break;
    case '212':
        $response['message'] = 'Die angefragte USt-IdNr. ist ungültig. Sie enthält ein unzulässiges Länderkennzeichen.';
        break;
    case '213':
        $response['message'] = 'Sie sind nicht zur Abfrage einer deutschen USt-IdNr. berechtigt.';
        break;
    case '214':
        $response['message'] = 'Ihre deutsche USt-IdNr. ist fehlerhaft. Sie beginnt mit \'DE\' gefolgt von 9 Ziffern.';
        break;
    case '215':
        $response['message'] = 'Ihre Anfrage enthält nicht alle notwendigen Angaben für eine einfache Bestätigungsanfrage.';
        break;
    case '216':
        $response['message'] = 'Ihre Anfrage enthält nicht alle notwendigen Angaben für eine qualifizierte Bestätigungsanfrage.';
        break;
    case '217':
        $response['message'] = 'Bei der Verarbeitung der Daten aus dem angefragten EU-Mitgliedstaat ist ein Fehler aufgetreten.';
        break;
    case '218':
        $response['message'] = 'Eine qualifizierte Bestätigung ist zur Zeit nicht möglich.';
        break;
    case '219':
        $response['message'] = 'Bei der Durchführung der qualifizierten Bestätigungsanfrage ist ein Fehler aufgetreten.';
        break;
    case '221':
        $response['message'] = 'Die Anfragedaten enthalten nicht alle notwendigen Parameter oder einen ungültigen Datentyp.';
        break;
    case '223':
        $response['message'] = 'Die angefragte USt-IdNr. ist gültig. Die Druckfunktion steht nicht mehr zur Verfügung.';
        break;
    case '999':
        $response['message'] = 'Eine Bearbeitung Ihrer Anfrage ist zurzeit nicht möglich. Bitte versuchen Sie es später noch einmal.';
        break;
    default:
        $response['message'] = $response['errorMessage'] ?: 'Unbekannter Fehler bei der Verarbeitung der Anfrage.';
}

echo json_encode($response); 