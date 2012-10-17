<input type="hidden" id="codeid" name="codeid" value="<?php echo $code['_id']['$id']; ?>">
<div id="code-full">
    <textarea id="editor" name="editor" style="display: none"><?php if(isset($code['realtime']) && $code['realtime'] == true) { echo "Loading..."; } else { echo htmlentities($code['code']); } ?></textarea>
</div>
<?php if(isset($code['realtime']) && $code['realtime']) { ?>
<div id="users" class="well">
        <ul class="nav nav-list">

        </ul>
</div>
<?php } ?>
<script type="text/javascript">
    var code = { 
            id: "<?php echo $code['_id']['$id']; ?>", 
            content: <?php echo json_encode($code['code']); ?>, 
            live: <?php echo ($code['realtime']) ? 'true' : 'false';; ?> 
    }; 
    $(function() { Ignite.Code.Compile(); });
</script>
