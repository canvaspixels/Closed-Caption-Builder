<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Closed Caption Builder</title>
	<script type="text/javascript">AC_FL_RunContent = 0;</script>
	<script src="AC_RunActiveContent.js" type="text/javascript"></script>
	<script src="jquery-1.6.1.min.js" type="text/javascript"></script>
	
	<style type="text/css">
	</style>
</head>
<body>
		<script type="text/javascript">
		$('#paste_insert').remove();
		$(document.body).append(
			'<form id="paste_insert" style="background:#EEE; padding:10px; border:1px solid #999; position:absolute; left:0; top:0;"><label for="paste_alignment" style="margin:0 9px 0 0;">Select alignment</label><select id="paste_alignment" style="margin:0 9px 0 0;"><option value="left">Left</option><option value="middle">Middle</option><option value="right">Right</option><select><button type="submit">Insert</button></form>'
		);
		$('#paste_alignment').focus();
		$('#paste_insert').submit(function(){
			alert($('#paste_alignment').val());
			$(this).remove();
			return false;
		});
		</script>
</body>
</html>