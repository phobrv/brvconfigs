<div class='box box-primary'>
	<form  class="form-horizontal ConfigForm"  enctype="multipart/form-data">
		<input type="hidden" name="type" value="web">
		@csrf
		<div class="box-body">
			@include('phobrv::input.inputSelect',['label'=>'Main Menu','key'=>'main_menu','array'=>$arrayMenu,'type'=>'configs'])
			@include('phobrv::input.inputText',['label'=>'Name Web','key'=>'site_name','type'=>'configs'])
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right">{{__('Submit')}}</button>
		</div>
	</form>
</div>

