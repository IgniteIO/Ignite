<?php $themes = $this->config->item('themes', 'ignite');$languages = $this->config->item('languages', 'ignite'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $this->lang->line('general_ignite'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ignite is a new way to share, run and save code.">
    <meta name="author" content="Axxim, LLC"/>
    <link rel="shortcut icon" href="<?php echo base_url('favicon.ico'); ?>">
    <!-- Le styles -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet">
    <!-- CodeMirror Themes -->
    <link href="<?php echo base_url('assets/libraries/codemirror/lib/codemirror.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/themes.css'); ?>" rel="stylesheet">
    <!-- End Themes -->
    <link href="<?php echo base_url('assets/libraries/codemirror/lib/util/dialog.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/ignite.css'); ?>" rel="stylesheet"/>
    <script src="<?php echo base_url('assets/libraries/modernizr.min.js'); ?>"></script>
    <?php if ($this->Account_model->loggedin()): ?>
    <script type="text/javascript">
        var account = {
            loggedin:true,
            username:"<?php echo $this->Account_model->username(); ?>"
        };
    </script>
    <?php endif; ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo base_url('assets/js/libraries/jquery-1.8.1.min.js'); ?>"><\/script>')</script>
</head>
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner hidden-phone">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="<?php echo base_url(); ?>"><i class="icon-fire"></i><span class="hidden-tablet"> <?php echo $this->lang->line('general_ignite'); ?></span></a>

                <div class="nav-collapse">
                    <ul class="nav">
                        <li class="active">
                            <div class="btn-group">
                                <a class="btn btn-primary play" href="#"><i class="icon-play icon-white"></i> <?php echo $this->lang->line('general_ignite'); ?></a>
                            </div>
                            <!-- /btn-group -->
                        </li>
                        <?php if ($this->uri->segment(1) == 'code') { ?>
                        <li>
                            <div class="btn-group">
                                <a class="btn btn-primary save" href="#"><i class="icon-download-alt icon-white"></i>
                                    <?php echo $this->lang->line('general_fork'); ?></a>
                            </div>
                        </li>
                        <?php } else { ?>
                        <li>
                            <div class="btn-group">
                                <a class="btn btn-primary save" href="#"><i class="icon-download-alt icon-white"></i>
                                    <?php echo $this->lang->line('general_save'); ?></a>
                            </div>
                        </li>
                        <?php } ?>
                        <?php if ($this->uri->segment(1) != 'code' || isset($code) && $code['user'] == $this->Account_model->username()) { ?>
                        <li>
                            <div class="btn-group">
                                <a class="btn btn-primary options" href="#"><i class="icon-cog icon-white"></i> <?php echo $this->lang->line('general_options'); ?></a>
                            </div>
                        </li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li>
                            <div class="btn-group">
                                <?php
                                if (isset($code['language']))
                                    $language = $code['language'];
                                else
                                    $language = 'php';
                                $elements = 'id="language" class="language span2" onchange="Ignite.SelectLanguage(this)"';
                                echo form_dropdown('language', $languages, $language, $elements);
                                ?>
                                <?php form_dropdown() ?>
                            </div>
                        </li>
                        <li>
                            <div class="btn-group">
                                <?php
                                $themes['default'] = 'CodeMirror';
                                if ($this->input->cookie('theme'))
                                    $theme = $this->input->cookie('theme');
                                else
                                    $theme = "neat";
                                $elements = 'id="theme" class="span2" onchange="Ignite.SelectTheme(this)"';
                                echo form_dropdown('theme', $themes, $theme, $elements);
                                ?>
                                <?php form_dropdown() ?>
                            </div>
                        </li>
                        <li>
                            <div class="btn-group hidden-tablet">
                                <a class="btn btn-primary about" href="#about">About</a>
                            </div>
                        </li>
                    </ul>
                    <ul class="nav pull-right" id="account">
                        <li>
                            <div class="btn-group hidden-tablet social">
                                <a class="btn btn-info" href="http://twitter.com/IgniteIO" target="_blank">@IgniteIO</a>
                            </div>
                        </li>
                        <li>
                            <div class="btn-group hidden-tablet bugs">
                                <a class="btn btn-warning" href="https://github.com/IgniteIO/Ignite"
                                   target="_blank">GitHub</a>
                            </div>
                        </li>
                        <?php if ($this->session->userdata('username')) { ?>
                        <li>
                            <div class="btn-group">
                                <button class="btn btn-primary"><?php echo $this->session->userdata('username'); ?></button>
                                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span
                                        class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><?php echo anchor('#fires', 'Your Fires', array('data-toggle' => 'modal', 'onclick' => 'Ignite.Fires()')); ?></li>
                                    <li class="divider"></li>
                                    <li><a href="#" class="logout">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                        <?php } else { ?>
                        <li>
                            <div class="btn-group">
                                <?php echo anchor('#login', 'Login', array('class' => 'btn btn-primary', 'data-toggle' => 'modal')); ?>
                            </div>
                        </li>
                        <li>
                            <div class="btn-group">
                                <?php echo anchor('#register', 'Register', array('class' => 'btn btn-primary', 'data-toggle' => 'modal')); ?>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <?php echo $content; ?>
        <div id="layer">
            <div class="top">
                <a class="btn btn-primary hide-layer" href="#" rel="tooltip" title="Close the Ignite Pane"><i
                        class="icon-forward icon-white"></i> Close</a>

                <div class="btn-toolbar">
                    <?php if ($this->uri->segment(1) == 'code') { ?><a class="btn btn-primary download" href="#"
                                                                       rel="tooltip" title="Download Your Fire">Download
                    Fire</a><?php } ?>
                    <div class="btn-group">
                        <a class="btn btn-info" rel="nofollow" href="javascript:void(0);"
                           onclick="window.open('https://twitter.com/share?url=<?php echo current_url(); ?>', '_blank', 'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0');"
                           rel="tooltip" title="Share on Twitter"><i class="icon-twitter"></i></a>
                        <a class="btn btn-info" rel="nofollow" href="javascript:void(0);"
                           onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo current_url(); ?>', '_blank', 'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0');"
                           rel="tooltip" title="Share on Facebook"><i class="icon-facebook"></i></a>
                        <a class="btn btn-info" rel="nofollow" href="javascript:void(0);"
                           onclick="window.open('https://plusone.google.com/_/+1/confirm?hl=en&amp;url=<?php echo current_url(); ?>', '_blank', 'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0');"
                           rel="tooltip" title="Share on Google+"><i class="icon-google-plus"></i></a>
                    </div>
                </div>
            </div>
            <!-- Display Embed Javascript -->
            <div id="content">
                <?php
                if ($this->uri->segment(1) == 'code') {
                    $value = "<script type=\"text/javascript\" src=\"" . base_url('/api/code/embed' . $code['_id']['$id'] . '.js') . "></script>";
                    ?>
                    <form class="form-inline embed">
                        <div class="input-prepend">
                            <span class="add-on">Embed JS</span><input type="text"
                                                                       value="<?php echo htmlentities($value); ?>"/>
                        </div>
                    </form>
                    <?php } ?>
                <h2>Output</h2>

                <div class="support">
                </div>
                <div id="default">
                    <pre></pre>
                </div>
            </div>
            <hr/>
            <div id="debugging" class="tabbable" style="margin-bottom: 18px;">
                <ul class="nav nav-pills">
                    <li class="active"><a href="#errors" data-toggle="tab">Errors</a></li>
                    <li><a href="#debug" data-toggle="tab">Debug</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="errors">
                        <div class="errors">
                        </div>
                    </div>
                    <div class="tab-pane" id="debug">
                        <dl>
                            <dt>Execution Time</dt>
                            <dd class="debug-time">Not Available</dd>
                            <dt>Version</dt>
                            <dd class="debug-version">Not Available</dd>
                            <dt>Used Memory</dt>
                            <dd class="debug-memory">Not Available</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="options">
        <button class="btn btn-primary hide-layer"><i class="icon-forward icon-white"></i> Close</button>
        <div class="content">
            <?php if ($this->uri->segment(1) == 'code') $fork = true; ?>
            <?php if (isset($fork)) { ?>
            <h1>Fork Options</h1>
            <p>These options will be set to your fork when you click the Fork button above.</p>
            <?php } else { ?>
            <h1>Fire Options</h1>
            <p></p>
            <?php } ?>
            <br/>

            <form class="form-horizontal" id="local-options">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="local-name">Fire Name</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge" id="local-name" name="local[name]"/>

                            <p class="help-block">Naming your fire will help in determining its contents later.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="local-realtime">Real Time Editing</label>

                        <div class="controls">
                            <input type="checkbox" id="local-realtime"
                                   name="local[realtime]"<?php ($this->config->item('live', 'ignite') ? ' checked' : ''); ?>>
                        </div>
                    </div>
                </fieldset>
            </form>
            <h1>Global Options</h1>

            <p>These options are specific to you while you use the site and collaborate with others.</p>
            <?php if (!$this->Account_model->loggedin()) { ?>
            <h2>You'll need to login to adjust/save these.</h2>
            <?php } else { ?>
            <form class="form-horizontal" id="global-options">
                <fieldset>
                    <!--<div class="control-group">
                    <label class="control-label" for="global[font_size]">Font Size</label>
                    <div class="controls">
                        <select id="font_size" name="global[font_size]" onchange="Ignite.Options.Set(this)">
                            <option value="10px">10px</option>
                            <option value="11px">11px</option>
                            <option value="12px" selected="selected">12px</option>
                            <option value="14px">14px</option>
                            <option value="16px">16px</option>
                            <option value="20px">20px</option>
                            <option value="24px">24px</option>
                        </select>
                    </div>
                    </div>-->
                    <p><strong>What happened to the other options?</strong> We're working on implementing a new editor at
                        the moment, they'll be back soon though!
                    </p>
                </fieldset>
            </form>
            <?php } ?>
        </div>
    </div>
    <div id="about">
        <button class="btn btn-primary hide-layer"><i class="icon-forward icon-white"></i> Close</button>
        <div class="content">
            <h2>What is Ignite?</h2>

            <p>Ignite is a new way to share, run and save code.</p>

            <p>You can get started by simply writing code, or pasting your existing code here. After that you can hit Play
                or Save. Playing code automatically compiles and saves it.
            </p>

            <p>If you find any problems feel free to join us on irc at irc.esper.net #axxim or email me at
                luke@axxim.net
            </p>

            <p>Site is copyright <a href="http://axxim.net/">Axxim, LLC</a>. Code is copyright the owner.</p>

            <h2>News</h2>
            <h4>Ignite now supports PHP</h4>

            <p>Ignite now fully supports PHP, feel free to check out these fires to get started: <a
                    href="http://ignite.io/code/4fa9402aef167b8e0f000000">Reddit API</a>, <a
                    href="http://ignite.io/code/4fa940c1ef167bbf0f000000">Loop</a>, <a
                    href="http://ignite.io/code/4fa90e28ef167b8b72000000">Fizz Buzz</a>.</p>
            <br/>
        </div>
    </div>
    <!--/.fluid-container-->
    <div id="login" class="modal hide fade">
        <form action="/account/login">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>

                <h3>Account Login</h3>
            </div>
            <div class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="username">Username</label>

                    <div class="controls">
                        <input type="text" class="input-xlarge" name="username" autofocus/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password">Password</label>

                    <div class="controls">
                        <input type="password" class="input-xlarge" name="password"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    <div id="register" class="modal hide fade">
        <form action="/account/register" method="POST">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>

                <h3>Account Registration</h3>
            </div>
            <div class="modal-body">
                <p>Registering an account lets you save snippets, fork them, bla and bla!</p>
                <br/>

                <div class="control-group">
                    <label class="control-label" for="username">Username</label>

                    <div class="controls">
                        <input type="text" class="input-xlarge" id="username" name="username" autofocus/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password">Password</label>

                    <div class="controls">
                        <input type="password" class="input-xlarge" id="password" name="password"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="confirmpassword">Confirm Password</label>

                    <div class="controls">
                        <input type="password" class="input-xlarge" id="confirmpassword" name="confirmpassword"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>

                    <div class="controls">
                        <input type="email" class="input-xlarge" id="email" name="email"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-register">Register</button>
            </div>
        </form>
    </div>
    <script id="fires-template" type="text/x-handlebars-template">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>

            <h3>Your Fires</h3>
        </div>
        <div class="modal-body">
            <p><a href="<?php echo base_url('/api/user/dump'); ?>/{{username}}" class="dump">Download all of my fires as a
                .zip</a></p>

            <p>Fires are basically snippets of code, here are yours: </p>
            <table class="table table-condensed">
                <thead>
                <th>Fire Name/ID</th>
                <th>Date</th>
                <th></th>
                </thead>
                <tbody>
                {{#fires}}
                <tr>
                    <td>
                        {{#if name}}
                        <a href="/code/{{_id.$id}}">{{name}}</a>
                        {{else}}
                        <a href="/code/{{_id.$id}}">{{_id.$id}}</a>
                        {{/if}}
                    </td>
                    <td>{{timestamp}}</td>
                    <td>
                        <!--<a href="#" data-id="{{_id.$id}}" class="delete" style="text-decoration: none; color: red;"><i class="icon-remove"></i></a>--></td>
                </tr>
                {{/fires}}
                </tbody>
            </table>
        </div>
    </script>
    <div id="fires" class="modal hide fade"></div>


    <script src="<?php echo base_url('assets/js/jquery-ui.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.cookie.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-tooltip.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-tour.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libraries/codemirror/lib/codemirror.js'); ?>"></script>
    <!-- CodeMirror Plugins -->
    <script src="<?php echo base_url('assets/libraries/codemirror/lib/util/loadmode.js'); ?>"></script>
    <!-- End CodeMirror Plugins -->
    <script src="<?php echo base_url('assets/js/handlebars.js'); ?>"></script>
    <?php if ($this->config->item('live', 'ignite')): ?>
    <script src="<?php echo $this->config->item('live-url', 'ignite'); ?>channel/bcsocket.js"></script>
    <script src="<?php echo $this->config->item('live-url', 'ignite'); ?>share/share.js"></script>
    <script src="<?php echo $this->config->item('live-url', 'ignite'); ?>share/cm.js"></script>
    <?php endif; ?>
    <script src="<?php echo base_url('assets/js/ignite.js'); ?>"></script>
    <script>
        var _gaq=[['_setAccount','UA-31526104-1'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>

</body>
</html>