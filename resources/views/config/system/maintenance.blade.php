<div class='box box-primary'>
	<div class="box-body">
		<div class="form-horizontal">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Maintenance</label>
				<div class="col-sm-10">
					<a id="btnTurnOn" href="#" onclick="maintenance(0)" class="btn btn-primary">
						Turn on website
					</a>
					<a id="btnTurnOff" href="#" onclick="maintenance(1)" class="btn btn-danger">
						Turn off website
					</a>
				</div>
			</div>
			@if ($data['configs']['maintenance'])
			<div class="form-group" id="form-link-private">
				<label for="inputEmail3" class="col-sm-2 control-label">Link private</label>
				<div class="col-sm-6">
					<input type="text" id="link-private" name="link-private" class="form-control" value="{{ $data['configs']['link_private'] ?? '#'  }}">
				</div>
				<div class="col-sm-2">
					<button class="btn btn-primary" onclick="copyToClipboard('#link-private')">Click to copy</button>
				</div>
			</div>
			@endif
		</div>
		<input type="hidden" id="isMainenance" name="isMainenance" value="{{ $data['configs']['maintenance'] }}">
	</div>
</div>
<script type="text/javascript">
	 function copyToClipboard(element) {
        $(element).select();
        document.execCommand("copy");
    }
	$(function(){
		var isMainenance = $('#isMainenance').val();
		console.log(isMainenance);
		changeBtnMaintenance(isMainenance);
	})

	function changeBtnMaintenance(isMainenance)
	{
		var i = parseInt(isMainenance)
		switch(i)
		{
			case 1:
			$('#btnTurnOn').css('display','block');
			$('#btnTurnOff').css('display','none');
			$('#form-link-private').css('display','block')
			break;
			default:
			$('#btnTurnOn').css('display','none');
			$('#btnTurnOff').css('display','block');
			$('#form-link-private').css('display','none')
			break;
		}
	}

	function maintenance(off)
	{
		if(off == 1 )
			var answer = confirm("Bạn muốn đóng website?");
		else
			var answer = confirm("Bạn muốn mở lại website?");

		if(answer)
		{
			$.ajax({
				headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				url: '{{URL::route("config.maintenanceWebsite")}}',
				type: 'POST',
				data: {off: off},
				success: function(output){
					$('#link-private').val(output['link_private'])
					changeBtnMaintenance(output['off']);
					alertOutput(output['msg'],output['message'])

				}
			});
		}


	}
</script>
