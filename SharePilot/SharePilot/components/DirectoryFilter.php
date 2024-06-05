<?php
namespace SharePilotV2\Components;

class DirectoryFilter extends \RecursiveFilterIterator {
    private $excludeDirs;
    private $excludedFiles;

    public function __construct(\RecursiveIterator $iterator, array $excludeDirs = [], array $excludedFiles = []) {
        parent::__construct($iterator);
        $this->excludeDirs = $excludeDirs;
        $this->excludedFiles = $excludedFiles;
    }

    public function accept(): bool {
        $item = $this->current();

        // Exclude specified directories
        if ($item->isDir() && in_array($item->getFilename(), $this->excludeDirs)) {
            return false;
        }

        // Exclude specific files (adjust as needed)
        if ($item->isFile()) {
            $filename = $item->getFilename();           
            if (in_array($filename, $this->excludedFiles) || $filename === 'manifest.json') {
                return false;
            }
        }

        return true;
    }
}