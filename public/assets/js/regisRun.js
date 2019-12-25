$(document).ready(function() {

	function clearCheck(){
		$("#run_1").children("span").hide(50);
		$("#run_2").children("span").hide(50);
		$("#run_3").children("span").hide(50);
	}

	$("#run_1").click(function(){
		if($("input[name=runType]").val()==$(this).data("type")){return;}
		clearCheck();
		$("input[name=runType]").val($(this).data("type"));
		$("#price").html($(this).data("price"));
		$(this).children("span").show(50);
	});
	$("#run_2").click(function(){
		if($("input[name=runType]").val()==$(this).data("type")){return;}
		clearCheck();
		$("input[name=runType]").val($(this).data("type"));
		$("#price").html($(this).data("price"));
		$(this).children("span").show(50);
	});
	$("#run_3").click(function(){
		if($("input[name=runType]").val()==$(this).data("type")){return;}
		clearCheck();
		$("input[name=runType]").val($(this).data("type"));
		$("#price").html($(this).data("price"));
		$(this).children("span").show(50);
	});

});