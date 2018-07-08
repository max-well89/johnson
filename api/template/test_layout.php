<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>API web-tester</title>

	<script src="/js/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" href="/css/bootstrap.min.css">
<!--	<link rel="stylesheet" href="/css/bootstrap-theme.min.css">-->
	<script src="/js/bootstrap.min.js"></script>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="/js/html5shiv.min.js"></script>
		<script src="/js/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<div class="col-lg-12"><h4>API testing module.</h4></div>

	<div id="parameters_panel" class="col-lg-4">
		<div>Выберите пользователя:</div>
		<div id="user_selector">
		</div>
		<div>Выберите команду:</div>
		<div id="request_selector">
		</div>
		<div id="request_parameters">
			<table class="table table-condensed">
				
			</table>
		</div>
		<div>
			<button type="button" id="btn_custom_json" class="btn btn-sm btn-default">Custom JSON</button>
			<button type="button" id="btn_format_json" class="btn btn-sm btn-primary" style="display: none;">Format JSON</button>
			<button type="button" id="btn_run_json" class="btn btn-sm btn-success">Run JSON</button>
		</div>
	</div>
	<div id="request_panel" class="col-lg-4">
		REQUEST
		<pre id="request_data"></pre>
	</div>
	<div id="response_panel" class="col-lg-4">
		RESPONSE
		<pre></pre>
	</div>
	
	<div id="doc_panel" class="col-lg-12">
	</div>
	
<script>

function makeRequest() {
	var request = {
		request: {
			action: $('#request_selector select').val(),
			params: {}
		}
	};
	$('#request_parameters input:checkbox:checked').each(function () {
		var checkbox = $(this);
		var pname = checkbox.attr('name');
		var pval = $('#request_parameters input[name="' + pname + '"]:text').val();
		if (pval == undefined) pval = '';
		request.request.params[pname] = pval;
	});
	$('#request_data').html(JSON.stringify(request, null, '\t'));
}


userSelectorHandler = function() {
	console.log($(this).val());
	$.getJSON('', {
			cmd: 'get_actions',
			user: $(this).val()
		}, function (data) {
			var inp = $('<select></select>');
			for (key in data) {
				var opt = $('<option></option>');
				opt.attr('value', key);
				opt.html(key + ' - ' + data[key]);
				inp.append(opt);
			}
			inp.change(requestSelectorHandler);
			$('#request_selector').html(inp);
			inp.change();
		}
	);
}


requestSelectorHandler = function() {
	$.getJSON('', {
			cmd: 'get_action_params',
			action: $(this).val()
		}, function (data) {
			var table = $('#request_parameters table');
			var request = {
				request: {
					action: $('#request_selector select').val(),
					params: {}
				}
			};
			table.html('');
			for (key in data) {
				var tr = $('<tr></tr>');
				var td = $('<td>' + data[key].name + '</td>');
				tr.append(td);
				var td = $('<td><input type="text" name="' + data[key].name + '" value="' + data[key].example + '" class="form-control"></td>');
				tr.append(td);
				var td = $('<td><input type="checkbox" name="' + data[key].name + '" ' + (data[key].required ? 'checked' : '') + '></td>');
				tr.append(td);
				table.append(tr);
				if (data[key].required) {
					request.request.params[data[key].name] = data[key].example;
				}
			}
			$('#request_data').html(JSON.stringify(request, null, '\t'));
			$('#request_parameters input:text').change(makeRequest);
			$('#request_parameters input:checkbox').change(makeRequest);
		}
	);
	$.get('', {
			cmd: 'get_action_doc',
			action: $(this).val()
		}, function(data) {
			$('#doc_panel').html(data);
		}
	);
}


function jsonUnescape(txt) {
	return txt.replace(/\\u([0-9a-f]{4})/g, function(str, p1, offset, s) {
		return String.fromCharCode("0x" + p1);
	});
}

$('#btn_run_json').click(function() {
	$('#btn_run_json').attr('disabled', 'disabled');
	if ($('#btn_custom_json').css('display') != 'none') {
		var request = $('#request_data').html();
	} else {
		var request = $('#request_data').val();
	}
	$.post('?cmd=run_json', {
		request: request,
		user: $('#user_selector select').val()
	}, function(data) {
		$('#response_panel pre').html(jsonUnescape(data));
		$('#btn_run_json').removeAttr('disabled');
	});
});

$('#btn_custom_json').click(function() {
	var tag = $('#request_data');
	tag.replaceWith($('<textarea id="request_data" class="form-control" rows=12">' + tag.html() + '</textarea>'));
	$('#btn_format_json').show();
	$('#btn_custom_json').hide();
});

$('#btn_format_json').click(function() {
	var tag = $('#request_data');
	tag.replaceWith($('<pre id="request_data">' + tag.html() + '</pre>'));
	$('#btn_custom_json').show();
	$('#btn_format_json').hide();
});

$.getJSON('', {
		cmd: 'get_users'
	}, function (data) {
		var inp = $('<select></select>');
		for (key in data) {
			var opt = $('<option></option>');
			opt.attr('value', data[key]);
			opt.html(data[key]);
			inp.append(opt);
		}	
		inp.change(userSelectorHandler);
		$('#user_selector').html(inp);
		inp.change();
	}
);

</script>
	
</body>
</html>
