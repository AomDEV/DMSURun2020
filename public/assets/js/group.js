$(document).ready(function(){
	$( ".runtype" ).change(function() {
		if(typeof backend !== 'undefined' && backend == true){
			$(".subtype").hide(0);
			$(".subTypeSelect").prop( "disabled", true );
			if($(this).val() == "1"){
				$(".subTypeSelect").html("");
				$(".subTypeSelect").append("<option value=0 selected>0</option>");
				$(".subTypeSelect").prop( "disabled", true );
			} else{
				$(".subtype").show(0);
				$(".subTypeSelect").html("");
				$(".subTypeSelect").prop( "disabled", true );
				var subType;var startSubType;var endSubType;
				if($(this).val() == "2"){
					$("#label").html("เลือกรุ่นอายุ");
					subType = subType1;startSubType=startType1;endSubType=endType1;
					$(".subTypeSelect").prop( "disabled", false );
				} else {
					$("#label").html("เลือกประเภทการวิ่ง");
					subType = subType2;startSubType=startType2;endSubType=endType2;
					$(".subTypeSelect").prop( "disabled", false );
				}
				for(var i=startSubType;i<endSubType;i++){
					$(".subTypeSelect").append("<option value="+i+">"+subType[i]+"</option>");
				}
			}
		} else{
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
		}
	});
});