<?php

class Config {

    public function getConfig($key = null) {
        $config = array(
            "path.pdf" => DEFAULT_PDF_OUTPUT_DIR,
            "path.swf" => DEFAULT_SWF_OUTPUT_DIR,
            "cmd.conversion.singledoc" => CMD_CONVERSION_SINGLEDOC,
            "cmd.conversion.splitpages" => CMD_CONVERSION_SPLITPAGES,
            "cmd.searching.extracttext" => CMD_SEARCHING_EXTRACTTEXT
        );
        if ($key !== null) {
            if (isset($config[$key])) {
                return $config[$key];
            } else {
                throw new Exception("Unknown key '$key' in configuration");
            }
        } else {
            return $config;
        }
    }

    public function setConfig($config) {
        $this->config = $config;
    }

    public function getDocUrl() {
        return "<br/><br/>Click <a href='http://flexpaper.devaldi.com/docs_php.jsp'>here</a> for more information on configuring FlexPaper with PHP";
    }

}
