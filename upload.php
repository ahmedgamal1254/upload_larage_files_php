<?php
$tempDir = 'chunks'; // folder chunks to store all chunks
$fileName = $_POST['name']; // file name
$fileIndex = $_POST['index']; // request index

// create folder chunks if not fount
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

move_uploaded_file($_FILES['file']['tmp_name'], "$tempDir/$fileName.part.$fileIndex");

$finalDir = 'uploads'; // final video after combine all chunks
$filePath = "$tempDir/$fileName.part.*"; // all files
$fileParts = glob($filePath); // return all files as a same pattern 
sort($fileParts, SORT_NATURAL); // sort all files by index part.0 , part.1 , part.2 etc...


$finalFile = fopen("$finalDir/$fileName", 'w+'); // write mp4 video by chunks

foreach ($fileParts as $filePart) {
    $chunk = file_get_contents($filePart);    
    fwrite($finalFile, $chunk);
    // unlink($filePart);  // Optionally delete the chunk
}

fclose($finalFile);

echo "ok";