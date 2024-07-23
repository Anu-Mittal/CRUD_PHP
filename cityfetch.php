<?php

header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod === 'POST') {
    handlePostRequest();
} else { 
    echo json_encode(['error' => 'Unsupported request method']);
}


function handlePostRequest() {
    // Read the raw POST data
    $data = file_get_contents('php://input');

    // Decode the JSON data to get the countryId
    $decodedData = json_decode($data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON']);
        return;
    }

    if (isset($decodedData['stateId'])) {
        $stateId = $decodedData['stateId'];
        
        include 'connect.php';
        $sql = "select * from Cities where stateid=$stateId";
        $result1 = $con->query($sql);

        $arr = [];

        while ($row = $result1->fetch_assoc()) {
            $arr[] = [
                'id' => $row['id'],
                'state_id' => $row['stateid'],
                'city_name' => $row['citynames'],
            ];
        }



        echo json_encode($arr);
    } else {
        echo json_encode(['error' => 'countryId not provided']);
    }
}

?>