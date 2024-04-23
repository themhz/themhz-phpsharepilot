<?php

namespace SharePilotV2\Components;

class UpdateManager {
    private $updateFilePath;
    private $tempDirectory;

    public function __construct($updateFilePath, $tempDirectory = 'temp/') {
        $this->updateFilePath = $updateFilePath;
        $this->tempDirectory = $tempDirectory;
    }

    // Generates a checksum for a given file
    public function generateChecksum($filePath) {
        
        if (!file_exists($filePath)) {        
            throw new \Exception("File does not exist.");            
        }
        return hash_file('sha256', $filePath);
    }

    // Verifies the checksum of a file against a provided checksum
    public function verifyChecksum($filePath, $providedChecksum) {
        $actualChecksum = $this->generateChecksum($filePath);
        return $actualChecksum === $providedChecksum;
    }

    // Downloads and extracts an update package, then applies update instructions
    public function downloadAndExtract($url) {
        // Download the file
        file_put_contents($this->updateFilePath, fopen($url, 'r'));

        // Check permissions and directory existence
        if (!$this->checkPermissions()) {
            throw new \Exception("Insufficient permissions.");
        }

        // Extract the zip file
        $zip = new \ZipArchive;
        if ($zip->open($this->updateFilePath) === TRUE) {
            $zip->extractTo($this->tempDirectory);
            $zip->close();
        } else {
            throw new \Exception("Failed to open or extract the zip file.");
        }

        // Read JSON instructions and apply updates
        $this->applyUpdateInstructions();
    }

    private function checkPermissions() {
        // Check if we can write to the target directory and read/write the temporary directory
        return is_writable(dirname($this->updateFilePath)) && is_writable($this->tempDirectory);
    }

    private function applyUpdateInstructions() {
        $jsonFilePath = $this->tempDirectory . 'instructions.json';
        if (!file_exists($jsonFilePath)) {
            throw new \Exception("Instructions JSON file not found.");
        }

        $instructions = json_decode(file_get_contents($jsonFilePath), true);
        foreach ($instructions as $file => $targetPath) {
            if (!copy($this->tempDirectory . $file, $targetPath)) {
                throw new \Exception("Failed to copy {$file} to {$targetPath}");
            }
        }
    }
}


//Exampke of how the json file is structured 
//{
//     "files": [
//       {
//         "source": "newindex.php",
//         "destination": "/var/www/html/index.php"
//       },
//       {
//         "source": "lib/newlib.php",
//         "destination": "/var/www/html/lib/lib.php"
//       },
//       {
//         "source": "images/logo.png",
//         "destination": "/var/www/html/images/logo.png"
//       }
//     ],
//     "folders": [
//       {
//         "source": "newassets/",
//         "destination": "/var/www/html/assets/"
//       }
//     ]
//   }
  


