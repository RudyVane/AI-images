<?php
session_start(); // Start a session to maintain the state

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('image_tags.db');
    }
}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
} else {
}

// Function to read images from a folder and process them
function processImages($folderPath, $tableName, $db) {
    $images = glob($folderPath . '/*.jpg'); // Change the file extension as needed

    if (empty($images)) {
        echo "No images found in the folder.";
        return;
    }

    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the tags and image URL from the POST data
        $tags = $_POST['tags'];
        $url = $_POST['image'];

        // Create a prepared statement
        $stmt = $db->prepare("INSERT INTO $tableName (`url`, `tags`) VALUES (:url, :tags)");

        // Bind the parameters
        $stmt->bindParam(':url', $url, SQLITE3_TEXT);
        $stmt->bindParam(':tags', $tags, SQLITE3_TEXT);

        // Execute the statement
        $result = $stmt->execute();

        // Check if the query was successful
        if ($result) {
            // Image tags inserted successfully
        } else {
            echo "Error inserting tags: " . $db->lastErrorMsg();
        }
    }

    // Initialize the current image index or get it from the session
    $currentImageIndex = isset($_SESSION['current_image']) ? $_SESSION['current_image'] : 0;
	
	
    // Check if there are more images to display
    if (isset($images[$currentImageIndex])) {
        $image = $images[$currentImageIndex];
        displayImage($image, $db);

        // Update the session variable to point to the next image
        $_SESSION['current_image'] = $currentImageIndex + 1;
    } else {
        // All images have been processed
        echo "All images have been processed.";
        // Reset the session variable if needed
        unset($_SESSION['current_image']);
    }
}

// Function to display an image and a form to input tags
function displayImage($image, $db) {
    echo '<div style="text-align:center">';
    echo '<img src="' . $image . '" width="200" height="200" /><br><br>';
    echo '<form method="POST" action="">';
    echo '<input type="hidden" name="image" value="' . $image . '" /><br />';
    echo '<input type="text" name="tags" size="100" placeholder="Enter tags (comma-separated)" /><br />';
    echo '<input type="submit" name="submit" value="Submit" />';
    echo '</form>';
    echo '</div>';
}

// Usage example
$folderPath = 'images2/';
$tableName = 'images';

processImages($folderPath, $tableName, $db);
?>
