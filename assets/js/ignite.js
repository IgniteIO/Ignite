var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "text/x-php",
    indentUnit: 4,
    indentWithTabs: true,
    enterMode: "keep",
    tabMode: "shift"
});


var Config = {
    CodeMirror: {
        modeURL: "../assets/libraries/codemirror/mode/%N/%N.js"
    }
};

var Ignite = {

    debug: true,
    size: "",
    Compiled: false,

    init: function() {		
        this.Editor();
        this.Responsive();
        this.Tour();
        this.KeyBinds();
        this.ToolTips();
        this.RealTime();
        this.Online();
    //this.Options();
    //this.Options.Load();
    },

    Chat: {
        Join: function() {
            sharejs.open(id, 'text', function(error, doc) {
                doc.submitOp({
                    i: data.code, 
                    p: 0
                });
                doc.close();
            });
        },
        Send: function() {
            $state.submitOp({
                p: ['chat',0],
                li: {
                    from: username,
                    message: e.srcElement.value
                }
            });
        }
    },

    Editor: function() {
        CodeMirror.modeURL = Config.CodeMirror.modeURL;
        
        if($.cookie('theme')) {
            editor.setOption("theme", $.cookie('theme'));
        } else {
            editor.setOption("theme", "neat");
        }
        editor.setOption("mode", $('#language').val());
        CodeMirror.autoLoadMode(editor, $('#language').val());
        
        editor.focus();
    },

    Responsive: function() {
        if(window.innerWidth < 480) {
            editor.renderer.setShowGutter(false);
        }
    /*
		if(window.innerWidth < 480)
			size = "mobile";
		else if(window.innerWidth < 767)
			size = "tablet";
		else if(window.innerWidth < 768)
			size = "portrait-tablet";
		else if(window.innerWidth < 980) 
			size = "default";
		else 
			size = "large";
         */
    },

    Tour: function() {
        var tour = new Tour();

        tour.addStep({
            element: ".brand",
            title: "Welcome to Ignite!",
            content: "Ignite is a new way to share, edit and compile code in real time. Follow the tour and I'll teach you how to use Ignite!",
            placement: "bottom"
        });

        tour.addStep({
            element: ".play",
            title: "The Ignite Button",
            content: "Clicking the Ignite button will instantly run your code, as shown on the left.",
            placement: "bottom",
            onShow: function (tour) {
                Ignite.Code.Compile();
            },
            onHide: function (tour) {
                $("#layer").hide(400);

                $("#editor").animate({
                    width: "100%"
                }, function() {
                    editor.resize();
                });
            }
        });

        tour.addStep({
            element: ".save",
            title: "The Save Button",
            content: "Clicking the save button will save the code and redirect you to the new code page, which you can then share with others to take advantage of real time editing!",
            placement: "bottom"
        });

        tour.addStep({
            element: ".options",
            title: "Options",
            content: "Options give you the ability to customize your Fire (code) and your experience while browsing the site, go here to name your code or change your font size.",
            placement: "bottom",
            onShow: function (tour) {
                $("#layer").hide(400);
                $("#info").hide(400);
                $("#about").hide(400);
                $("#users").hide(400);

                $("#options").show(400);
                $("#editor").animate({
                    width: "60%"
                }, function() {
                    editor.resize();
                });
            },
            onHide: function (tour) {
                $("#options").hide(400);

                $("#editor").animate({
                    width: "100%"
                }, function() {
                    editor.resize();
                });
            }
        });

        tour.addStep({
            element: "#account",
            title: "That's about it!",
            content: "Make sure you consider signing up so you can save your code for later and use the chat function on real time documents!",
            placement: "bottom"
        });

        tour.start();
    },

    ToolTips: function() {
        $('.play').tooltip({
            title: "You can Ignite code with CTRL + Enter too!",
            placement: "bottom"
        });

        $('.save').tooltip({
            title: "Saves code and enable real time editing.",
            placement: "bottom"
        });

        $('.language').tooltip({
            title: "Igniteable means you're able to run the code and see its output.",
            placement: "bottom"
        });

        $('.options').tooltip({
            title: "Give your code a name and enable/disable other functions. ",
            placement: "bottom"
        });

        $('a[rel=tooltip]').tooltip({
            "placement": "bottom"
        });
    },

    SelectLanguage: function(language) {
        var language = language.options[language.selectedIndex].innerHTML.toLowerCase();

        $.cookie('language', language, {
            expires: 365, 
            domain: '.ignite.io', 
            path: '/'
        });
        editor.setOption("mode", language);
        CodeMirror.autoLoadMode(editor, language);
    },

    SelectTheme: function(theme) {
        var theme = $("#theme").val();

        $.cookie('theme', theme, {
            expires: 365, 
            domain: '.ignite.io', 
            path: '/'
        });
        editor.setOption("theme", theme);
    },

    Code: {
        Save: function() {
            var $form = $( this ),
            code = editor.getValue(),
            codeid = $("#codeid").val(),
            language = $("#language").val(),
            name = $("#local-name").val(),
            realtime = $("#local-realtime").prop('checked');

            $('.output pre').html('');
            $('.errors pre').html('');

            /* Send the data using post and put the results in a div */
            $.post( "/api/code/commit", {
                code: encodeURIComponent(code), 
                language: language, 
                id: codeid, 
                name: name, 
                realtime: realtime
            },
            function( data ) {
                //console.log(data);
                data = JSON.parse(data);
                if(data.status === "error") {
                    $('.errors pre').text(data.errors);
                } else {
                    var id = data.id;
                    if(realtime) {
                        sharejs.open(id, 'text', 'https://ignite.io/channel', function(error, doc) {
                            doc.insert(0, code);
                            doc.close();
                        });
                    }
                    window.location = "/code/"+id;
                }
					
            }
            )
            .error(function( data ) { 
				
                });
        },

        Compile: function() {
            $(".support").text('');
            $(".output pre").text('');
            var $form = $( this ),
            code = editor.getValue(),
            codeid = $("#codeid").val(),
            language = $("#language").val();

            $.post( "/api/code/compile", {
                code: encodeURIComponent(code), 
                language: language
            },
            function( data ) {
                console.log(data);
                data = JSON.parse(data);
					
                if(data.status === "error") {
                    $('#default pre').text(data.message);
                } else {
                    $('#default pre').html(data.output);
                    $('#html').html(unescape(data.output));
                    $('.errors').html(data.errors);

                    if(data.earray != null) {
                        line = data.earray.line - 1;
                        vert = editor.getLine(line).length;
                        editor.setCursor(data.earray.line - 1, vert);
                        editor.focus();
                    }
                }
                if(data.debug != null) {
                    $('.debug-time').text(data.debug.time);
                    $('.debug-version').text(data.debug.version);
                    $('.debug-memory').text(data.debug.memory / 1024 + 'kb');
                }

                if(data.errors == null) $('#debugging a[href="#debug"]').tab('show');
                else $('#debugging a[href="#errors"]').tab('show');
					
            }
            );
            //if(Ignite.Compiled == false) Ignite.RealTimeCompile();
            Ignite.Compiled = true;

            if(language == "python") $(".support").html("Python Support is provided by <a href='http://eval.appspot.com/' target='_blank'>Google's Python Eval Appspot</a>");
            if(language == "ruby") $(".support").html("Ruby Support is provided by <a href='http://rubyfiddle.com/' target='_blank'>RubyFiddle</a>");

            $("#options").hide(400);
            $("#about").hide(400);
            $("#info").hide(400);

            $("#layer").show(400);
            $("#editor").animate({
                width: "60%"
            }, function() {
                //editor.refresh();
                editor.setSize("60%", "100%");
            });
        } 
    },

    KeyBinds: function(e) {
        $(document).keypress(function (e) {
            // ctrl + enter,  ctrl + b
            if (e.ctrlKey && ((e.keyCode == 10 || e.charCode == 10) || (e.keyCode == 13 || e.charCode == 13) || (e.keyCode == 66 || e.charCode == 66))) {
                e.preventDefault(); // otherwise we get extra spaces in the code
				
                Ignite.Code.Compile();
            }
        });
    },

    RealTimeCompile: function() {
        if(typeof code != 'undefined' && code.live === true) {
            console.log("Compiled");
            sharejs.open(code.id + '-compile', 'text', function(error, doc) {
                doc.insert(0, "c");
                doc.close();
            });
        }
    },

    RealTime: function() {
        if(typeof code != 'undefined' && code.live === true) {
            console.log(new Date().getTime() + ": Real Time Enabled");
			
            sharejs.open(code.id, 'text', 'https://ignite.io/channel', function(error, doc) {
                console.log(new Date().getTime() + ": Opening ");
                
                if (error) {
                    console.error(error);
                    return;
                }
                
                console.log(doc.getText());

                if(doc.getText() == '' || doc.getText() == null) {
                    console.log(new Date().getTime() + ": Filling the document with text.");
					
                    editor.setValue(code.content);
                    doc.insert(0, code.content);
                    doc.attach_codemirror(editor);
                } else {
                    console.log(new Date().getTime() + ": Setting Document");
                    editor.setValue(doc.getText());

                    doc.attach_codemirror(editor);
                }
            });
        }
    },

    Online: function() {
        if(typeof code != 'undefined') {

            function userList() {
                $.post( "/api/user/getOnline/"+code.id,
                    function( data ) {
                        console.log(data)
                        data = JSON.parse(data);
                        if(data.status === "error") {
                            $('.errors pre').text(data.errors);
                        } else {
                            $('#users .nav').empty();
                            for(x in data.users) {
                                $('#users .nav').append('<li><a href="#">'+data.users[x]+'</a></li>');
                            }
                        }	
                    }
                    );
            };

            function online() {
                $.post("/api/user/online/" + code.id);
            }

            function offline() {
                $.post("/api/user/offline/" + code.id);
            }

            window.onload = function(e) {
                online();
            };

            window.onbeforeunload = function (e) {
                offline();
            };

            userList();
            setInterval(function(){
                userList()
            },5000);
        }
    },

    Options: {
        Settings: {
            Global: {
                font_size: "12px",
                highlight_active_line: true,
                animate_scrolling: true,
                show_print_margin: false
            }
        },
        Load: function() {
            if($.cookie('settings')) {
                settings = $.cookie('settings');
                settings = JSON.parse(settings);
                Ignite.Options.Settings.Global = settings;
            } 
            settings = Ignite.Options.Settings.Global;

            document.getElementById('editor').style.fontSize = settings.font_size;
            $("#font_size").val(settings.font_size);

            //editor.setKeyboardHandler(settings.key_binding);
            //$("#key_binding").val(settings.key_binding);

            editor.setHighlightActiveLine(settings.highlight_active_line);
            $("#highlight_active_line").attr('checked', settings.highlight_active_line);

            editor.setAnimatedScroll(settings.animate_scrolling);
            $("#animate_scrolling").attr('checked', settings.animate_scrolling);

            editor.setShowPrintMargin(settings.show_print_margin);
            $("#show_print_margin").attr('checked', settings.show_print_margin);
        },

        Set: function(setting) {

            if(setting.name == "global[font_size]") {
                Ignite.Options.Settings.Global.font_size = setting.value;
                document.getElementById('editor').style.fontSize = setting.value;
            } else if(setting.name == "global[key_binding]") {
                Ignite.Options.Settings.Global.key_binding = setting.value;
                editor.setKeyboardHandler(setting.value);
            } else if(setting.name == "global[highlight_active_line]") {
                Ignite.Options.Settings.Global.highlight_active_line = setting.checked;
                editor.setHighlightActiveLine(setting.checked);
            } else if(setting.name == "global[animate_scrolling]") {
                Ignite.Options.Settings.Global.animate_scrolling = setting.checked;
                editor.setAnimatedScroll(setting.checked);
            } else if(setting.name == "global[show_print_margin]") {
                Ignite.Options.Settings.Global.show_print_margin = setting.checked;
                editor.setShowPrintMargin(setting.checked);
            }

            //$.cookie('settings', JSON.stringify(Ignite.Options.Settings.Global));
            $.cookie('settings', JSON.stringify(Ignite.Options.Settings.Global), {
                expires: 365, 
                domain: '.ignite.io', 
                path: '/'
            });
        },
        Get: function() {

        }
    },
    
    /*
	Options: function(name) {
		$("#global-options :input").once("change", function() {
			if()
			document.getElementById('editor').style.fontSize = $(this).val();
		});
		$("#myForm :input")
			.once("change", function() {
				// do whatever you need to do when something's changed.
				// perhaps set up an onExit function on the window
				$('#saveButton').show();

				// now remove the event handler from all the elements
				// since you don't need it any more.
				$("#myForm :input").unbind("change");
			})
		;
	},
     */
    Modes: function() {

    },

    Fires: function() {
        var source   = $("#fires-template").html();
        var template = Handlebars.compile(source);

        $.get("/api/fires/getAll/"+account.username,
            function( data ) {
                data = JSON.parse(data);
				
                $("#fires").html(template({
                    fires: data.fires,
                    username: account.username
                }));
            }
            );

		
    }

};

Ignite.init();

$(document).ready(function () {

    /* Makes the width of the quick-start modal dynamic */
    $('#quick-start').modal({
        backdrop: true,
        keyboard: true
    }).css({
        width: 'auto',
        'margin-left': function () {
            return -($(this).width() / 2);
        }
    });

    $("#register").submit(function(event) {

        /* stop form from submitting normally */
        event.preventDefault(); 

        /* get some values from elements on the page: */
        var $form = $( this ),
        username = $form.find( 'input[name="username"]' ).val(),
        email = $form.find( 'input[name="email"]' ).val(),
        password = $form.find( 'input[name="password"]' ).val(),
        confirmpassword = $form.find( 'input[name="confirmpassword"]' ).val(),
        url = $form.attr( 'action' );

        /* Send the data using post and put the results in a div */
        $.post( "/account/register", {
            username: username, 
            email: email, 
            password: password, 
            confirmpassword: confirmpassword
        },
        function( data ) {
            $(".modal-header h3").text('Registering...');
            $("#register .modal-body").slideUp('slow');
            console.log(data);
            data = JSON.parse(data);
            if(data.status === "error") {
                $(".modal-body .alert").hide();
                $(".modal-header h3").text('Error!');
                $("#register .modal-body").slideDown('fast');
                $(".modal-body").prepend("<div class=\"alert alert-error\">There was a problem submitting your form, the error is as follows:<br><ul><li>" + data.message + "</li></ul></div>" );
            } else {
                $(".modal-header h3").text('Account Registered!');

                $("#register .btn-register").fadeOut('slow');
                $("#register .modal-body").slideUp('slow', function() { 
                    $("#register .modal-footer").hide("slow"); 
                    $(this).html(data.message); 
                }).slideDown('slow');
                $("#account").load(location.href+" #account>*","");
            }
				
        }
        )
        .error(function( data ) { 
			
            });

    });

    $("#login").submit(function(event) {

        /* stop form from submitting normally */
        event.preventDefault(); 

        /* get some values from elements on the page: */
        var $form = $( this ),
        username = $form.find( 'input[name="username"]' ).val(),
        password = $form.find( 'input[name="password"]' ).val(),
        url = $form.attr( 'action' );

        /* Send the data using post and put the results in a div */
        $.post( "/account/login", {
            username: username, 
            password: password
        },
        function( data ) {
            console.log(data);
            data = JSON.parse(data);
            if(data.status === "error") {
                $("#login .modal-body .alert").hide();
                $("#login .modal-body").prepend("<div class=\"alert alert-error\">There was a problem submitting your form, the error is as follows:<br><ul><li>" + data.message + "</li></ul></div>" );
            } else {
                $('#login').modal('hide');
                $("#account").load(location.href+" #account>*","");

                var account = {
                    loggedin: true,
                    username: username
                };
            }
				
        }
        )
        .error(function( data ) { 
			
            });

    });

    $(".stack").click(function() {
        alert("Coming soon!");
    });

    $(".logout").click(function() {
        $.post("/account/logout", function(data) {
            $("#account").load(location.href+" #account>*","");
        });
    });


    $('.play').click(function() {
        $("#users").hide(400);
        Ignite.Code.Compile();
        return false;
    });

    $('.save').click(function() {
        Ignite.Code.Save();
        return false;
    });

    $('.about').click(function() {
        $("#layer").hide(400);
        $("#info").hide(400);
        $("#options").hide(400);
        $("#users").hide(400);

        $("#about").show(400);
        $("#editor").animate({
            width: "60%"
        }, function() {
            editor.setSize("60%", "100%");
        });
    });


    $('.options').click(function() {
        $("#layer").hide(400);
        $("#info").hide(400);
        $("#about").hide(400);
        $("#users").hide(400);

        $("#options").show(400);
        $("#editor").animate({
            width: "60%"
        }, function() {
            editor.setSize("60%", "100%");
        });
    });


    $('.hide-layer').click(function() {
        $("#info").hide(400);
        $("#layer").hide(400);
        $("#about").hide(400);
        $("#options").hide(400);

        $("#users").show(400);
        $("#editor").animate({
            width: "100%"
        }, function() {
            editor.setSize("100%", "100%");
        });
    });

    $('.download').click(function() {
        var url = document.URL;
        var id = url.substr(url.lastIndexOf('/') + 1);

        window.location = "/api/code/download/"+id;
        return false;
    });

});