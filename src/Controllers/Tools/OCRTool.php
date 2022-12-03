<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRTool extends Controller {
    public function read() {
        //echo (new TesseractOCR('C:\\Users\\Admin\\Downloads\\testocr.png'))->run();
        echo shell_exec('C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe C:\\Users\\Admin\\Downloads\\testocr.png');
    }
}
