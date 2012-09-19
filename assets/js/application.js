/* Keep Track of Online */
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
	setInterval(function(){userList()},5000);
}

/* Bootstrap Javascript Options */
$('.play').tooltip({
	title: "You can Ignite code with CTRL + Enter too!",
	placement: "bottom"
});

$('.save').tooltip({
	title: "Saves code and enable real time editing.",
	placement: "bottom"
});

$('a[rel=tooltip]').tooltip({"placement": "bottom"});

/* Tour */
function tour() {
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
			compileCode();
		},
		onHide: function (tour) {
			$("#layer").hide(400);

			$("#editor").animate({width: "100%"}, function() { editor.resize(); });
		}
	});

	tour.addStep({
		element: ".save",
		title: "The Save Button",
		content: "Clicking the save button will save the code and redirect you to the new code page, which you can then share with others to take advantage of real time editing!",
		placement: "bottom"
	});

	tour.addStep({
		element: "#account",
		title: "That's about it!",
		content: "Make sure you consider signing up so you can save your code for later and use the chat function on real time documents!",
		placement: "bottom"
	});

	
	
	// Disable in public
	tour.start();
}

tour();


/* ACE Editor */
var editor = ace.edit("editor");

if($.cookie('theme')) {
	editor.setTheme($.cookie('theme'));
} else {
	editor.setTheme("ace/theme/crimson_editor");
}
editor.getSession().setMode("ace/mode/" + $('#language').val());
editor.getSession().setUseWrapMode(true);
editor.setShowPrintMargin(false);

/* Real Time */
if(typeof code != 'undefined' && code.live === true) {
	console.log(new Date().getTime() + ": Real Time Enabled");
	
	sharejs.open(code.id, 'text', function(error, doc) {
		console.log(new Date().getTime() + ": Opening ");

		if(doc.getText() == '' || doc.getText() == null) {
			console.log(new Date().getTime() + ": Document Exists in Real Time");
			
			doc.insert(0, code.content);
			doc.attach_ace(editor);
		} else {
			console.log(new Date().getTime() + ": Document Doesn't Exist in Real Time ");

			doc.attach_ace(editor);
		}
	});
}

/* Other Javascript */

function selectLanguage(language) {
	var language = language.options[language.selectedIndex].innerHTML.toLowerCase();

	$.cookie('language', language, { expires: 365, domain: '.ignite.io', path: '/' });
	editor.getSession().setMode("ace/mode/" + language);
}

function selectTheme(theme) {
	var theme = $("#theme").val();

	$.cookie('theme', theme, { expires: 365, domain: '.ignite.io', path: '/' });
	editor.setTheme(theme);
}

function saveCode() {
	var $form = $( this ),
		code = editor.getSession().getValue(),
		codeid = $("#codeid").val(),
		language = $("#language").val();

	$('.output pre').html('');
	$('.errors pre').html('');

	/* Send the data using post and put the results in a div */
	$.post( "/api/code/commit", { code: code, language: language, id: codeid },
		function( data ) {
			data = JSON.parse(data);
			if(data.status === "error") {
				$('.errors pre').text(data.errors);
			} else {
				var id = data.id;
				sharejs.open(id, 'text', function(error, doc) {
					doc.submitOp({
						i: data.code, 
						p: 0
					});
					doc.close();
				});
				window.location = "/code/"+id;
			}
			
		}
	)
	.error(function( data ) { 
		
	});
}

function compileCode() {
	var $form = $( this ),
		code = editor.getSession().getValue(),
		codeid = $("#codeid").val(),
		language = $("#language").val();

	$.post( "/api/code/compile", { code: code, language: language },
		function( data ) {
			console.log(data);
			data = JSON.parse(data);
			
			if(data.status === "error") {
				$('.errors pre').text(data.errors);
			} else {
				$('.output pre').html(data.output);
				$('.errors').html(data.errors);

				if(data.earray != null) {
					editor.gotoLine(data.earray.line);
				}
			}
			
		}
	)
	.error(function( data ) { 
	});

	$("#options").hide(400);
	$("#about").hide(400);
	$("#info").hide(400);

	$("#layer").show(400);
	$("#editor").animate({width: "60%"}, function() { editor.resize(); });
}

/* Local and Global Settings */
function setLocalSetting(id, setting, value) {



}

$(document).keypress(function (e) {
	// ctrl + enter,  ctrl + b
	if (e.ctrlKey && ((e.keyCode == 10 || e.charCode == 10) || (e.keyCode == 13 || e.charCode == 13) || (e.keyCode == 66 || e.charCode == 66))) {
		e.preventDefault(); // otherwise we get extra spaces in the code
		
		compileCode();
	}
});

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
		$.post( "/account/register", { username: username, email: email, password: password, confirmpassword: confirmpassword },
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
		$.post( "/account/login", { username: username, password: password },
			function( data ) {
				console.log(data);
				data = JSON.parse(data);
				if(data.status === "error") {
					$("#login .modal-body .alert").hide();
					$("#login .modal-body").prepend("<div class=\"alert alert-error\">There was a problem submitting your form, the error is as follows:<br><ul><li>" + data.message + "</li></ul></div>" );
				} else {
					$('#login').modal('hide');
					$("#account").load(location.href+" #account>*","");
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
		compileCode();
	});

	$('.save').click(function() {
		saveCode();
	});

	$('.about').click(function() {
		$("#layer").hide(400);
		$("#info").hide(400);
		$("#options").hide(400);
		$("#users").hide(400);

		$("#about").show(400);
		$("#editor").animate({width: "60%"}, function() { editor.resize(); });
	});


	$('.options').click(function() {
		$("#layer").hide(400);
		$("#info").hide(400);
		$("#about").hide(400);
		$("#users").hide(400);

		$("#options").show(400);
		$("#editor").animate({width: "60%"}, function() { editor.resize(); });
	});


	$('.hide-layer').click(function() {
		$("#info").hide(400);
		$("#layer").hide(400);
		$("#about").hide(400);
		$("#options").hide(400);

		$("#users").show(400);
		$("#editor").animate({width: "100%"}, function() { editor.resize(); });
	});

	$('.download').click(function() {
		var url = document.URL;
		var id = url.substr(url.lastIndexOf('/') + 1);

		window.location = "/api/code/download/"+id; 
	});

});

/*
function ignite() {

	function compile() {

	}

	function save() {

	}

}
*/


var Ignite = {
	getInfo: function () {
		return this.color + ' ' + this.type + ' apple';
	}
}