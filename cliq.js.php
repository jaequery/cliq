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

    loadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js", function () {
    	loadCliq();
    });

    function loadCliq() {

	var cliq = {
	    validate: function(el){
		return false;
	    },
	    form: {
		activate: function(){
		    $(document).ready(function(){
			$('form').attr('onsubmit', '');
			$('form').submit(function(e){
			    e.preventDefault();
			    if(!cliq.validate($('form'))){
				alert('not valid');
				return false;
			    }
			    var data = {settings: {from: "<?php echo $_GET['from']; ?>", to: "<?php echo $_GET['to']; ?>"}, form: {} };
			    $('form :input').each(function(k,v){
				if( ($(this).attr('type') != 'Submit' || $(this).attr('type') != 'button') && (this.name || $(this).is('textarea')) ){
				    var label = $(this).parent().find('label').text() || $(this).parent().text();
				    data.form[k] = {label: $.trim(label), value: $.trim($(this).val())};
				}
			    });

			    $('form :input').attr('disabled', true);
			    $(this).find('input[type=Submit]').attr('disabled', true).val('Submitting ...');

			    console.log(data);
			    var url = "http://jien.jaequery.com/cli/email";
			    /*$.getJSON(url + "?callback=?", data, function(res) {
			      $('form').html("Thank you!");
			      });*/

			    return false;
			});
		    });
		}
	    }
	}
	cliq.form.activate();

    }


})();
