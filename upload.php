<?php
class UploadLarageFile{
    public $chunks="chunks"; // in laravel storage/chunks
    protected $uploads="upload";

    protected $fileName='';
    protected $fileIndex='';

    public function __construct($request){
        $this->fileName = $request['name']; // file name
        $this->fileIndex = $request['index']; // request index
    }

    public function upload_chunks(){
        move_uploaded_file($_FILES['file']['tmp_name'], "$this->chunks/$this->fileName.part.$this->fileIndex");
    }

    public function create_folder(){
        mkdir($this->chunks);
    }

    public function combine_all_chunks_and_create_file(){
        $finalDir = 'uploads'; // final video after combine all chunks
        $filePath = "$this->chunks/$this->fileName.part.*"; // all files
        $fileParts = glob($filePath); // return all files as a same pattern 
        sort($fileParts, SORT_NATURAL); // sort all files by index part.0 , part.1 , part.2 etc...
    
    
        $finalFile = fopen("$finalDir/$this->fileName", 'w+'); // write mp4 video by put all chunks in sequance
    
        foreach ($fileParts as $filePart) {
            $chunk = file_get_contents($filePart);    
            fwrite($finalFile, $chunk);
            // unlink($filePart);  // Optionally delete the chunk
        }

        fclose($finalFile);
    }

    public function response(){
        return json_encode([
            "status"=>200,
            "message"=> "ok"
        ]);
    }

}

try{
    $upload=new UploadLarageFile($_POST);
    if(!is_dir($upload->chunks)){
        $upload->create_folder();
    }
    $upload->upload_chunks(); // send chunk file from request after resized it to parts 1mb
    // combine all parts into original extenstion 
    $upload->combine_all_chunks_and_create_file();

    echo $upload->response();
}catch (Exception $e){
    echo $e->getMessage();
}