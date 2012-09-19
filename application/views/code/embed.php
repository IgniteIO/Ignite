<?php
$mimes = array('hqx' => 'application/mac-binhex40',
    'cpt' => 'application/mac-compactpro',
    'csv' => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
    'bin' => 'application/macbinary',
    'dms' => 'application/octet-stream',
    'lha' => 'application/octet-stream',
    'lzh' => 'application/octet-stream',
    'exe' => array('application/octet-stream', 'application/x-msdownload'),
    'class' => 'application/octet-stream',
    'psd' => 'application/x-photoshop',
    'so' => 'application/octet-stream',
    'sea' => 'application/octet-stream',
    'dll' => 'application/octet-stream',
    'oda' => 'application/oda',
    'pdf' => array('application/pdf', 'application/x-download'),
    'ai' => 'application/postscript',
    'eps' => 'application/postscript',
    'ps' => 'application/postscript',
    'smi' => 'application/smil',
    'smil' => 'application/smil',
    'mif' => 'application/vnd.mif',
    'xls' => array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
    'ppt' => array('application/powerpoint', 'application/vnd.ms-powerpoint'),
    'wbxml' => 'application/wbxml',
    'wmlc' => 'application/wmlc',
    'dcr' => 'application/x-director',
    'dir' => 'application/x-director',
    'dxr' => 'application/x-director',
    'dvi' => 'application/x-dvi',
    'gtar' => 'application/x-gtar',
    'gz' => 'application/x-gzip',
    'php' => 'text/x-php',
    'js' => 'application/x-javascript',
    'swf' => 'application/x-shockwave-flash',
    'sit' => 'application/x-stuffit',
    'tar' => 'application/x-tar',
    'tgz' => array('application/x-tar', 'application/x-gzip-compressed'),
    'xhtml' => 'application/xhtml+xml',
    'xht' => 'application/xhtml+xml',
    'zip' => array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
    'mid' => 'audio/midi',
    'midi' => 'audio/midi',
    'mpga' => 'audio/mpeg',
    'mp2' => 'audio/mpeg',
    'mp3' => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
    'aif' => 'audio/x-aiff',
    'aiff' => 'audio/x-aiff',
    'aifc' => 'audio/x-aiff',
    'ram' => 'audio/x-pn-realaudio',
    'rm' => 'audio/x-pn-realaudio',
    'rpm' => 'audio/x-pn-realaudio-plugin',
    'ra' => 'audio/x-realaudio',
    'rv' => 'video/vnd.rn-realvideo',
    'wav' => array('audio/x-wav', 'audio/wave', 'audio/wav'),
    'bmp' => array('image/bmp', 'image/x-windows-bmp'),
    'gif' => 'image/gif',
    'jpeg' => array('image/jpeg', 'image/pjpeg'),
    'jpg' => array('image/jpeg', 'image/pjpeg'),
    'jpe' => array('image/jpeg', 'image/pjpeg'),
    'png' => array('image/png', 'image/x-png'),
    'tiff' => 'image/tiff',
    'tif' => 'image/tiff',
    'css' => 'text/css',
    'html' => 'text/html',
    'htm' => 'text/html',
    'shtml' => 'text/html',
    'txt' => 'text/plain',
    'text' => 'text/plain',
    'log' => array('text/plain', 'text/x-log'),
    'rtx' => 'text/richtext',
    'rtf' => 'text/rtf',
    'xml' => 'text/xml',
    'xsl' => 'text/xml',
    'mpeg' => 'video/mpeg',
    'mpg' => 'video/mpeg',
    'mpe' => 'video/mpeg',
    'qt' => 'video/quicktime',
    'mov' => 'video/quicktime',
    'avi' => 'video/x-msvideo',
    'movie' => 'video/x-sgi-movie',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'word' => array('application/msword', 'application/octet-stream'),
    'xl' => 'application/excel',
    'eml' => 'message/rfc822',
    'json' => array('application/json', 'text/json')
);

?>
<link href="<?php echo base_url('assets/libraries/codemirror/theme/neat.css'); ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/libraries/codemirror/lib/codemirror.js'); ?>"></script>
<script src="<?php echo base_url('assets/libraries/codemirror/lib/util/runmode.js'); ?>"></script>
<script src="<?php echo base_url('assets/libraries/codemirror/mode/xml/xml.js'); ?>"></script>
<script src="<?php echo base_url('assets/libraries/codemirror/mode/javascript/javascript.js'); ?>"></script>
<script src="<?php echo base_url('assets/libraries/codemirror/mode/css/css.js'); ?>"></script>
<script src="<?php echo base_url('assets/libraries/codemirror/mode/clike/clike.js'); ?>"></script>
<script src="<?php echo base_url('assets/libraries/codemirror/mode/'.$doc['language'].'/'.$doc['language'].'.js'); ?>"></script>
<script type="text/javascript">
    function main() {
        CodeMirror.runMode(document.getElementById("ignite-raw-code").value, "<?php echo $mimes[$doc['language']]; ?>", document.getElementById("ignite-code"));
        
        console.log("main() loaded for <?= $doc['_id']['$id'] ?>");
        height = jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-code").height();
        jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-content").height(height);
        jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-output").height(height);
        jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-toggle").click(function() {
            if(jQuery('#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-output pre').text() == 'Running...') run();
            jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-header .ignite-right .ignite-show-code").toggle();
            jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-code").slideToggle("fast");
            
            jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-header .ignite-right .ignite-show-output").toggle();
            jQuery("#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-output").slideToggle("fast");
            return false;
        });
        
        function run() {
            jQuery.post( "<?php echo base_url('api/code/compile'); ?>", {
                code: <?php echo json_encode(urlencode($doc['code'])); ?>, 
                language: <?php echo json_encode($doc['language']); ?>
            },
            function( data ) {
                data = JSON.parse(data);
                console.log(data);

                if(data.status === "error") {
                    jQuery('#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-output pre').text(data.message);
                } else {
                    jQuery('#ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-output pre').text(data.output);
                }

            }
        );
        };
    };
    if (typeof jQuery === "undefined") {
        var script_tag = document.createElement('script');
        script_tag.setAttribute("type","text/javascript");
        script_tag.setAttribute("src", "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js")
        script_tag.onload = main; // Run main() once jQuery has loaded
        script_tag.onreadystatechange = function () { // Same thing but for IE
            if (this.readyState == 'complete' || this.readyState == 'loaded') main();
        }
        document.getElementsByTagName("head")[0].appendChild(script_tag);
    } else {
        main();
    }
</script>
<style type="text/css">
    #ignite-<?= $doc['_id']['$id'] ?> {
        margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
        
        font: 100% monospace;
        border: 1px solid #cccbc9;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-header {
        background-color: #f3f3f3;
        border-bottom: 1px solid #cccbc9;
        font-family: Monospace;
        padding: 1px;
        padding-left: 5px;
        padding-right: 5px;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-header .ignite-left {
        float: left;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-header .ignite-right {
        float: right;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-content {
        padding: 5px;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-content code {
        
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-raw-code {
        display: none;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-content .ignite-output {
        font-family: monospace;
        display: none;
        overflow-y: auto;
    }
    #ignite-<?= $doc['_id']['$id'] ?> .ignite-header .ignite-right .ignite-show-code {
        display: none;
    }
</style>
<div id="ignite-<?= $doc['_id']['$id'] ?>" class="ignite-embed">
    <div class="ignite-header">
        <div class="ignite-left"><?php if (!empty($doc['name'])) echo $doc['name']; else echo 'Powered by <a href="http://ignite.io/">Ignite</a>'; ?></div>
        <div class="ignite-right">
            <a href="#" class="ignite-toggle ignite-show-code">Show Code</a><a href="#" class="ignite-toggle ignite-show-output">Run Code</a> 
            | <a href="http://ignite.io/code/<?= $doc['_id']['$id'] ?>">Edit on Ignite</a>
        </div>
        <div style="clear: both"></div>
    </div>
    <div class="ignite-content">
        <textarea id="ignite-raw-code" class="ignite-raw-code"><?php echo $doc['code']; ?></textarea>
        <div id="ignite-code" class="ignite-code cm-s-neat"></div>
        <div class="ignite-output"><pre>Running...</pre></div> 
    </div>

</div>