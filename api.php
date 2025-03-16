<?php
// Configuration - Replace with your actual API key
$apiKey = "AIzaSyBlvPtLj0A2udVIuyOx2B7EEfXaG6ltzO0";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = [];

    try {
        // Text processing
        if (!empty($_POST['text'])) {
            $text = trim($_POST['text']);
            $response['text_analysis'] = analyzeNaturalLanguageAPI($text, $apiKey);
        }

        // File processing
        if (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileType = mime_content_type($fileTmpPath);

            if (str_starts_with($fileType, 'image/')) {
                $response['image_analysis'] = analyzeVisionAPI($fileTmpPath, $apiKey, true);
            } elseif (str_starts_with($fileType, 'video/')) {
                $response['video_analysis'] = analyzeVideoAPI($fileTmpPath, $apiKey);
            } else {
                throw new Exception("Unsupported file type: $fileType");
            }
        }

        if (empty($response)) {
            throw new Exception("No content provided for analysis");
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}

// If not POST request
http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
exit;

// ----------------- API HELPER FUNCTIONS -----------------

function analyzeVisionAPI($imagePath, $apiKey, $isLocal = false) {
    $url = "https://vision.googleapis.com/v1/images:annotate?key=$apiKey";

    $imageData = [];
    if ($isLocal) {
        $imageContent = base64_encode(file_get_contents($imagePath));
        $imageData = ["content" => $imageContent];
    } else {
        $imageData = ["source" => ["imageUri" => $imagePath]];
    }

    $data = [
        "requests" => [[
            "image" => $imageData,
            "features" => [
                ["type" => "SAFE_SEARCH_DETECTION"],
                ["type" => "LABEL_DETECTION"],
                ["type" => "TEXT_DETECTION"]
            ]
        ]]
    ];

    return sendPostRequest($url, $data);
}

function analyzeNaturalLanguageAPI($text, $apiKey) {
    $url = "https://language.googleapis.com/v1/documents:analyzeSentiment?key=$apiKey";

    $data = [
        "document" => [
            "type" => "PLAIN_TEXT",
            "content" => $text,
            "language" => "en"
        ],
        "encodingType" => "UTF8"
    ];

    return sendPostRequest($url, $data);
}

// ----------- NEW FUNCTION TO PROCESS VIDEO FILES -----------

function analyzeVideoAPI($videoPath, $apiKey) {
    // Step 1: Upload video to Google Cloud Storage (Assumes video is already in a public GCS bucket)
    $gcsUri = uploadToGoogleCloudStorage($videoPath);

    if (!$gcsUri) {
        throw new Exception("Failed to upload video to Google Cloud Storage.");
    }

    // Step 2: Send request to Video Intelligence API
    $url = "https://videointelligence.googleapis.com/v1/videos:annotate?key=$apiKey";

    $data = [
        "inputUri" => $gcsUri,
        "features" => ["SAFE_SEARCH_DETECTION"]
    ];

    return sendPostRequest($url, $data);
}

// Function to upload video to Google Cloud Storage (Assumes bucket is public)
function uploadToGoogleCloudStorage($videoPath) {
    $bucketName = "your-bucket-name";  // Replace with your actual GCS bucket name
    $fileName = basename($videoPath);
    $gcsUri = "gs://$bucketName/$fileName";

    // Move the uploaded file to the GCS bucket (Assuming you have write permissions)
    $destination = "/path/to/google-cloud-sdk/bin/gsutil cp $videoPath $gcsUri";  // Adjust path
    exec($destination, $output, $status);

    return $status === 0 ? $gcsUri : false;
}

// General function to send POST requests
function sendPostRequest($url, $data) {
    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data),
            "ignore_errors" => true
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        throw new Exception("API request failed");
    }

    $result = json_decode($response, true);
    
    if (isset($result['error'])) {
        throw new Exception("API Error: " . $result['error']['message']);
    }
    return $result;
}
?>
