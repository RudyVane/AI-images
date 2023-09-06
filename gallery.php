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

// Function to retrieve images and tags from the database
function getImagesAndTags($db) {
    $query = "SELECT DISTINCT url, tags FROM images";
    $result = $db->query($query);
    $data = array();

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

// Get all images and tags from the database
$imagesAndTags = getImagesAndTags($db);
?>

<!DOCTYPE html>
<html>
<html lang=”en”>
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<meta name="description" content="Ontdek prachtige, door AI gegenereerde afbeeldingen op onze website. Blader door een enorme collectie gratis te downloaden kunstwerken. Verrijk je projecten met unieke en creatieve visuals.">

<head>
    <title>AI Generated Image Gallery</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
<div style="position:absolute; text-align:left; top:1vw;">
<img src="logo.png" width="50" height="50" alt="Logo">
</div>
<button id="aboutButton" style="z-index: 10;position: absolute; top: 0; right: 0; width: 20vw;" onclick="window.location.href='text.html'">About this site</button>


<div style="position:absolute;width: 100%; text-align:center; top:1vw;">

    <h1>AI generated images</h1>
	<p>Combine tags for funny images</p>
	
	 <button id="showButton">Show tags</button>
    <button id="hideButton">Hide tags</button>
	
    <button onclick="clearCheckboxes()">Clear All</button>
    <div id="content" style="display: none;">
        <div class="checkbox-container">
        <?php
        $allTags = array();

        foreach ($imagesAndTags as $item) {
            $tags = explode(',', $item['tags']);
            $tags = array_map('trim', $tags); // Remove spaces after commas
            $allTags = array_merge($allTags, $tags);
        }

        $uniqueTags = array_unique($allTags);
        sort($uniqueTags); // Sort tags alphabetically

        foreach ($uniqueTags as $tag) {
            echo '<div class="checkbox-item">';
            echo '<label><input type="checkbox" name="tags[]" value="' . $tag . '" onchange="filterImages()" />' . $tag . '</label>';
            echo '</div>';
        }
        ?>
    </div>
	</div>

    <!-- Image Gallery -->
    <div class="image-gallery">
        <?php
        foreach ($imagesAndTags as $item) {
            $tags = explode(',', $item['tags']);
            $tags = array_map('trim', $tags); // Remove spaces after commas
            echo '<div class="image-item" data-tags="' . implode(',', $tags) . '">';
            echo '<img src="' . $item['url'] . '" alt="Image" onclick="openPopup(\'' . $item['url'] . '\')" />';
            echo '</div>';
        }
        ?>
    </div><br>
		<p>This site has been visited <span id="counter">0</span> times since August 28, 2023.</p>
</div>
    

    <!-- Image Popup -->
    <div id="imagePopup" class="popup" onclick="closePopup()">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <img id="popupImg">
        </div>
    </div>

    <script>
         // Get references to the buttons and the content div
        var showButton = document.getElementById('showButton');
        var hideButton = document.getElementById('hideButton');
        var contentDiv = document.getElementById('content');

        // Add click event listener to the Show button
        showButton.addEventListener('click', function() {
            contentDiv.style.display = 'block'; // Show the content
        });

        // Add click event listener to the Hide button
        hideButton.addEventListener('click', function() {
            contentDiv.style.display = 'none'; // Hide the content
        });
		
    
        // Functie om de tellerwaarde op te halen uit de lokale opslag
        function getCounter() {
            var count = localStorage.getItem('visitCount');
            return count ? parseInt(count) : 0;
        }

        // Functie om de tellerwaarde te verhogen en bij te werken
        function updateCounter() {
            var currentCount = getCounter();
            var newCount = currentCount + 1;
            localStorage.setItem('visitCount', newCount);
            return newCount;
        }

        // Functie om de tellerwaarde op de pagina weer te geven
        function displayCounter() {
            var counterElement = document.getElementById('counter');
            counterElement.textContent = getCounter();
        }

        // Roep de functies aan om de teller weer te geven en bij te werken
        var updatedCount = updateCounter();
        displayCounter();
    </script>
</body>
</html>
