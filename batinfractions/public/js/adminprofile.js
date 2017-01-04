/**
 * Here are some scripts used on administrateprofile page, mostly for dynamic forms.
 */
$(function() {
	$('.timepicker').datetimepicker();
	
	$('#ban-server-chooser').change(function() {
		var value = this.value;
		if(value == "Global ban"){
			$('#ban-server').attr("type", "hidden");
			$('#ban-server').attr("value", "(global)");
		}else{
			$('#ban-server').attr("type", "text");
			$('#ban-server').attr("value", "");
		}
	});
	
	$('#ban-expiration-chooser').change(function() {
		var value = this.value;
		if(value == "Definitive ban"){
			$('#ban-expiration').attr("type", "hidden");
			$('#ban-expiration').attr("value", "definitive");
		}else{
			$('#ban-expiration').attr("type", "text");
			$('#ban-expiration').attr("value", "");
			$('#ban-expiration').data("DateTimePicker").setMinDate(new Date());
		}
	});
	
	$('#mute-server-chooser').change(function() {
		var value = this.value;
		if(value == "Global mute"){
			$('#mute-server').attr("type", "hidden");
			$('#mute-server').attr("value", "(global)");
		}else{
			$('#mute-server').attr("type", "text");
			$('#mute-server').attr("value", "");
		}
	});
	
	$('#mute-expiration-chooser').change(function() {
		var value = this.value;
		if(value == "Definitive mute"){
			$('#mute-expiration').attr("type", "hidden");
			$('#mute-expiration').attr("value", "definitive");
		}else{
			$('#mute-expiration').attr("type", "text");
			$('#mute-expiration').attr("value", "");
			$('#mute-expiration').data("DateTimePicker").setMinDate(new Date());
		}
	});
});