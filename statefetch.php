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

    if (isset($decodedData['countryId'])) {
        $countryId = $decodedData['countryId'];
        
        include 'connect.php';
        $sql = "select * from em_states where country_id=$countryId";
        $result1 = $con->query($sql);

        $arr = [];

        while ($row = $result1->fetch_assoc()) {
            $arr[] = [
                'id' => $row['id'],
                'cntry_id' => $row['country_id'],
                'state_name' => $row['state_name'],
            ];
        }



        echo json_encode($arr);
    } else {
        echo json_encode(['error' => 'countryId not provided']);
    }
}

?>