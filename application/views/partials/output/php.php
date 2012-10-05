<div class="top">
    <a class="btn btn-primary hide-layer" href="#" rel="tooltip" data-original-title="Close the Ignite Pane"><i class="icon-forward icon-white"></i> Close</a>
</div>

<div id="content">
    <h2>Raw Output</h2>
    <div id="default">
        <pre><?php echo $output['raw']; ?></pre>
    </div>
</div>

<hr>

<div id="debugging" class="tabbable">
    <ul class="nav nav-pills">
        <li><a href="#errors" data-toggle="tab">Errors</a></li>
        <li class="active"><a href="#debug" data-toggle="tab">Debug</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" id="errors">
            <div class="errors">

            </div>
        </div>
        <div class="tab-pane active" id="debug">
            <dl>
                <dt>Execution Time</dt>
                <dd class="debug-time">0.00001978874206543</dd>

                <dt>Version</dt>
                <dd class="debug-version">5.3.6-13ubuntu3.9</dd>

                <dt>Used Memory</dt>
                <dd class="debug-memory">910.21875kb</dd>
            </dl>
        </div>
    </div>
</div>
