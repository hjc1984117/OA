<?php
require '../../application.php';
require '../../loader-api.php';
?>
<!DOCTYPE html>
<html dir="ltr" mozdisallowselectionprint moznomarginboxes>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="google" content="notranslate">
        <title>星密码制度文在线件浏览</title>
        <link rel="stylesheet" href="../../assets/global/plugins/pdf2html5/css/viewer.css"/>
        <link rel="resource" type="application/l10n" href="../../assets/global/plugins/pdf2html5/locale/locale.properties"/>
        <script src="../../assets/global/plugins/html5.js"></script>
        <script src="../../assets/global/plugins/pdf2html5/js/compatibility.js"></script>
        <script src="../../assets/global/plugins/pdf2html5/js/l10n.js"></script>
        <script src="../../assets/global/plugins/pdf2html5/js/pdf.js"></script>
        <script src="../../assets/global/plugins/pdf2html5/js/debugger.js"></script>
        <script src="../../assets/global/plugins/pdf2html5/js/viewer.js"></script>
        <link rel="shortcut icon" type="image/ico" href="../../assets/global/img/favicon.ico">
    </head>
    <body tabindex="1" class="loadingInProgress" onselectstart="return false;" onpaste="return false;" oncopy="return false;" oncut="return false;" oncontextmenu="return false;">
        <div id="mainContainer">
            <div class="toolbar">
                <div id="toolbarContainer">
                    <div id="toolbarViewer">
                        <div class="my_center_toolbar">
                            <div class="my_split_toolbar_Button">
                                <input type="number" id="pageNumber" class="toolbarField pageNumber" value="1" size="4" min="1" tabindex="15">
                                <span id="numPages" class="toolbarLabel"></span>
                            </div>
                            <div class="splitToolbarButton">
                                <a href="#" class="toolbarButton_pageUp" title="Previous Page" id="previous" tabindex="13" data-l10n-id="previous"></a>
                                <a href="#" class="toolbarButton_pageDown" title="Next Page" id="next" tabindex="14" data-l10n-id="next"></a>
                            </div>
                            <div class="splitToolbarButton">
                                <a href="#" id="zoomOut" class="toolbarButton_zoomOut" title="Zoom Out" tabindex="21" data-l10n-id="zoom_out"></a>
                                <a href="#" id="zoomIn" class="toolbarButton_zoomIn" title="Zoom In" tabindex="22" data-l10n-id="zoom_in"></a>                                    
                            </div>                                
                        </div>
                    </div>
                    <div id="loadingBar">
                        <div class="progress">
                            <div class="glimmer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="viewerContainer" tabindex="0">
                <div id="viewer" class="pdfViewer"></div>
            </div>
            <div id="errorWrapper" hidden='true'>
                <div id="errorMessageLeft">
                    <span id="errorMessage"></span>
                    <button id="errorShowMore" data-l10n-id="error_more_info">
                        More Information
                    </button>
                    <button id="errorShowLess" data-l10n-id="error_less_info" hidden='true'>
                        Less Information
                    </button>
                </div>
                <div id="errorMessageRight">
                    <button id="errorClose" data-l10n-id="error_close">
                        Close
                    </button>
                </div>
                <div class="clearBoth"></div>
                <textarea id="errorMoreInfo" hidden='true' readonly="readonly"></textarea>
            </div>
        </div> <!-- mainContainer -->
    </body>
</html>