<?php

class Word2Pdf {

    private function MakePropertyValue($name, $value, $osm) {
        $oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
        $oStruct->Name = $name;
        $oStruct->Value = $value;
        return $oStruct;
    }

    private function createPdf($doc_url, $output_url) {
        $osm = new COM("com.sun.star.ServiceManager")or die("Please be sure that OpenOffice.org is installed.\n");
        $args = array($this->MakePropertyValue("Hidden", true, $osm));
        $oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");
        $oWriterDoc = $oDesktop->loadComponentFromURL($doc_url, "_blank", 0, $args);
        $export_args = array($this->MakePropertyValue("FilterName", "writer_pdf_Export", $osm));
        $oWriterDoc->storeToURL($output_url, $export_args);
        $oWriterDoc->close(true);
    }

    public function getInitials($filename) {
        $doc_file = "file:///" . DEFAULT_FILE_UPLOAD_DIR . $filename;
        $output_file = "file:///" . DEFAULT_PDF_OUTPUT_DIR . $filename . ".pdf";
        $this->createPdf($doc_file, $output_file);
    }

}
