<?php

use App\Helpers\DocxReader;
use Illuminate\Support\Facades\File;

if (!function_exists('read_docx')) {

    /**
     * description
     *
     * @internal param $
     */
    function read_docx($filename){

        $content = '';

        if(!$filename || !file_exists($filename)) return false;

        $zip = zip_open($filename);
        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }
        zip_close($zip);
//        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
//        $content = str_replace('</w:r></w:p>', "\r\n", $content);
//        $content = strip_tags($content);

        return $content;
    }
}

if (!function_exists('docxReader')) {

    /**
     * description
     *
     * @internal param $
     */
    function docxReader($filename)
    {
        $doc = new DocxReader();
        $doc->setFile($filename);

        if (!$doc->get_errors()) {
            $html = $doc->to_html();
            $plain_text = $doc->to_plain_text();

            return $html;
        }
        return implode(', ', $doc->get_errors());
    }
}
