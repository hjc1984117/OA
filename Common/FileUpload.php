<?php

class FileUpload {

    public function getInitials($file, $filename) {
        if ($file["error"] > 0) {
            return array("code" => 'Failure');
        } else {
            move_uploaded_file($file["tmp_name"], DEFAULT_FILE_UPLOAD_DIR . $filename);
            return array('code' => 'Success', "name" => $filename, "type" => $file["type"], "size" => ($file["size"] / 1024), "path" => DEFAULT_FILE_UPLOAD_DIR . $filename);
        }
    }

}
