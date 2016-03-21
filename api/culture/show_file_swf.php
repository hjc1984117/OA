<?php
require_once("../../application.php");
require '../../loader-api.php';
require_once("../../Common/pdf2swf/lib/config.php");
require_once("../../Common/pdf2swf/lib/common.php");
$configManager = new Config();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
    <head>
        <title>星密码OA管理系统</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width" />
        <link rel="stylesheet" type="text/css" href="../../assets/global/pdf2swf/css/flexpaper.css" />
        <link rel="shortcut icon" type="image/ico" href="../../assets/global/img/favicon.ico"/>

        <script src="../../assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
        <script src="../../assets/global/pdf2swf/js/flexpaper.js" type="text/javascript"></script>
        <script src="../../assets/global/pdf2swf/js/flexpaper_handlers.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
        $doc = "Paper.pdf";
        if (isset($_GET["doc"])) {
            $doc = $_GET["doc"];
            $doc = base64_decode($doc);
        }
        $pdfFilePath = $configManager->getConfig('path.pdf') . $doc;
        $swfFilePath = $configManager->getConfig('path.swf');
        ?> 
        <div style="position:absolute;height: 100%;width: 100%;">
            <div id="documentViewer" style="width:100%;height:100%"></div>
            <?php if (validPdfParams($pdfFilePath, $doc, null) && is_dir($swfFilePath)) { ?>
                <script type="text/javascript">
                    $('#documentViewer').FlexPaperViewer(
                            {config: {
                                    SWFFile: escape('../../../Common/pdf2swf/services/view.php?doc=<?php print $doc; ?>'),
                                    jsDirectory: '../../assets/global/pdf2swf',
									
                                    Scale: 0.6,
                                    ZoomTransition: 'easeOut',
									key : "$9e8ecbcb42493fed8d7",
                                    ZoomTime: 0.5,
                                    ZoomInterval: 0.2,
                                    FitPageOnLoad: true,
                                    FitWidthOnLoad: true,
                                    FullScreenAsMaxWindow: false,
                                    ProgressiveLoading: false,
                                    MinZoomSize: 0.2,
                                    MaxZoomSize: 5,
                                    SearchMatchAll: false,
									
                                    InitViewMode: 'Portrait',
                                    RenderingOrder: 'flash,html5',
                                    StartAtPage: '',
									
                                    ViewModeToolsVisible: true,
                                    ZoomToolsVisible: true,
                                    NavToolsVisible: true,
                                    CursorToolsVisible: true,
                                    SearchToolsVisible: true,
                                    PrintEnabled: true,
                                    WMode: 'window',
                                    localeChain: 'zh_CN'
                                }}
                    );
                </script>
            <?php } else { ?>
                <script>
                    alert('抱歉,该文件已经被删除了.');
                    window.close();
                </script>
            <?php } ?>
        </div>
    </body>
</html>