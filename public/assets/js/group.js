$(document).ready(function(){
	$( ".runtype" ).change(function() {
		$(".subtype_"+$(this).data("index")).hide(0);
		if($(this).val() == "1"){
			$(".subTypeSelect_"+$(this).data("index")).html("");
			$(".subTypeSelect_"+$(this).data("index")).append("<option value=0 selected>0</option>");
		} else{
			$(".subtype_"+$(this).data("index")).show(0);
			$(".subTypeSelect_"+$(this).data("index")).html("");
			var subType;var startSubType;var endSubType;
			if($(this).val() == "2"){
				$("#label_"+$(this).data("index")).html("เลือกรุ่นอายุ");
				subType = subType1;startSubType=startType1;endSubType=endType1;
			} else {
				$("#label_"+$(this).data("index")).html("เลือกประเภทการวิ่ง");
				subType = subType2;startSubType=startType2;endSubType=endType2;
			}
			for(var i=startSubType;i<endSubType;i++){
				$(".subTypeSelect_"+$(this).data("index")).append("<option value="+i+">"+subType[i]+"</option>");
			}
		}
	});
});