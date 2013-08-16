(function () {

    function loadScript(url, callback) {
        var script = document.createElement("script")
        script.type = "text/javascript";
        if (script.readyState) { //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else { //Others
            script.onload = function () {
                callback();
            };
        }
        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
    }

    loadScript("/cliq/inc/lazyload.js", function () {
	var scripts = ['//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', '//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.0/jquery.qtip.min.js'];
	var styles = ['//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.0/jquery.qtip.min.css'];
	LazyLoad.css(styles, function () {});
	LazyLoad.js(scripts, function () {
	    cliq.form.init();
	});

    });

    var cliq = {
	util: {
	    validateEmail: function(email){
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	    }
	},
	form: {
	    validate: function(el){

		var errors = [];
		$('.qtip').remove();
		$('.form-item.required :input').each(function(k, v){
		    var input = $(v);

		    if(input.attr('name')){
			if(input.attr('name').match(/email/)){
			    var is_email = cliq.util.validateEmail(input.val());
			    if(!is_email){
				errors.push(input);
			    }
			}
		    }

		    if(input.val() == ''){
			errors.push(input);
		    }

		});

		if(errors && errors[0]){

		    errors[0].focus();
		    var tip_text = 'This field is required';

		    if(errors[0].attr('name') && errors[0].val() != '' && errors[0].attr('name').match(/email/)){
			tip_text = 'Email is invalid';
		    }

		    errors[0].qtip({
			content: tip_text,
			position: {
			    my: 'bottom center',  // Position my top left...
			    at: 'top center', // at the bottom right of...
			    target: errors[0] // my target
			},
			show: {
			    when: false, // Don't specify a show event
			    ready: true // Show the tooltip when ready
			},
			hide: false // Don't specify a hide event
		    });

		    return false;
		}
		return true;
	    },
	    init: function(){
		$(document).ready(function(){
		    $('form').attr('onsubmit', '');
		    $('form').submit(function(e){
			e.preventDefault();
			if(!cliq.form.validate($('form'))){
			    return false;
			}
			var data = {settings: {from: "<?php echo $_GET['from']; ?>", to: "<?php echo $_GET['to']; ?>"}, form: {} };
			$('form :input').each(function(k,v){
			    if( ($(this).attr('type') != 'Submit' || $(this).attr('type') != 'button') && (this.name || $(this).is('textarea')) ){
				var label = $(this).parent().find('label').text() || $(this).parent().text();
				label = $.trim(label);
				var val = $.trim($(this).val());
				data.form[k] = {label: label, value: val};
			    }
			});

			$('form :input').attr('disabled', true);
			$(this).find('input[type=Submit]').attr('disabled', true).val('Submitting ...');

			var url = "http://jien.jaequery.com/cli/email";
			$.getJSON(url + "?callback=?", data, function(res) {
			  $('form').html("Thank you!");
			});

			return false;
		    });
		});
	    }
	}
    }

})();
