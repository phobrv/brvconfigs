<div class='box box-primary'>
	<form  class="form-horizontal ConfigForm"  enctype="multipart/form-data">
		<input type="hidden" name="type" value="web">
		@csrf
		<div class="box-body">
			@include('phobrv::input.label',['label'=>'Contact'])
			@include('phobrv::input.inputText',['label'=>'Hotline number','key'=>'hotline_number','type'=>'configs'])
			@include('phobrv::input.inputText',['label'=>'Email','key'=>'company_email','type'=>'configs'])
			@include('phobrv::input.label',['label'=>'Info'])
			@include('phobrv::input.inputText',['label'=>'Tên công ty','key'=>'company_name','type'=>'configs'])
			@include('phobrv::input.inputText',['label'=>'Address','key'=>'company_add','type'=>'configs'])
			@include('phobrv::input.label',['label'=>'Copyright'])
			@include('phobrv::input.inputText',['label'=>'Copyright','key'=>'copyright','type'=>'configs'])
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right">{{__('Submit')}}</button>
			</div>
		</form>
	</div>
</div>
