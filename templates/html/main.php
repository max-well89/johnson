<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $this->context->getConfigVal('title'); ?></title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="/css/jasny-bootstrap.min.css">
		<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
		<link rel="stylesheet" href="/js/chosen/chosen.css">
		<link rel="stylesheet" href="/css/stat.css">
		
		<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    	<script src="/js/moment-with-locales.js"></script>
		<script src="/js/jasny-bootstrap.min.js"></script>
    	<script src="/js/bootstrap-datetimepicker.min.js"></script>
    	<script src="/js/jquery.numberMask.min.js"></script>
    	
		<script src="/js/chosen/chosen.jquery.js" type="text/javascript"></script>
		<script src="/js/chosen/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
				
		<script src="/js/stat.js" type="text/javascript"></script>
	</head>
	<body>
	
	<div id="header">
		<a href="/" id="logo"></a>
	</div>
	
	<div class="navbar navbar-inverse navbar-static-top" role="navigation">
		<?php echo $menu; ?>
	</div>
    
    <div class="info_content"><?php echo $content; ?></div>
	
	<div class="navbar-fixed-bottom row-fluid">
		<div class="navbar-inner">
			<div class="container">
				
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="mainModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

  </body>
  
	<script type="text/javascript">
    	var config = {
			'.chosen-select'           : { width: 'auto', min_width: '100px' },
			'.chosen-select-deselect'  : {allow_single_deselect:true},
			'.chosen-select-no-single' : {disable_search_threshold:10},
			'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
			'.chosen-select-width'     : {width: "95%"}
		}
		for (var selector in config) {
			$(selector).each(function() { $(this).chosen(config[selector]); });
		}
	</script>
</html>
