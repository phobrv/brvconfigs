<div class='box box-primary'>
	<form  class="form-horizontal ConfigForm" enctype="multipart/form-data">
		<input type="hidden" name="type" value="web">
		@csrf
		<div class="box-body">
			<label class="font16">Header</label>
			@include('phobrv::input.inputImage',['label'=>'Logo','key'=>'logo_img','width'=>'100px','type'=>'configs'])
			@include('phobrv::input.inputText',['label'=>'ALT Logo','key'=>'logo_alt','type'=>'configs'])
			@include('phobrv::input.inputImage',['label'=>'Favicon','key'=>'favicon','width'=>'50px','type'=>'configs'])
			<hr class="border-light">
			<label class="font16">Footer</label>
			@include('phobrv::input.inputImage',['label'=>'Logo Footer','key'=>'logo_footer_img','width'=>'100px','type'=>'configs'])
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right">{{__('update')}}</button>
		</div>
	</form>
</div>


