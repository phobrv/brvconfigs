<div class='box box-primary'>
	<form  class="form-horizontal ConfigForm"  enctype="multipart/form-data">
		<input type="hidden" name="type" value="web">
		@csrf
		<div class="box-body">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Tempalte Web</label>
				<div class="col-sm-10">
					{{ Form::text('theme',$arrayTheme[config('option.theme')],array('class'=>'form-control','placeholder'=>'Theme','readonly'=>'readonly')) }}
				</div>
			</div>
			@include('phobrv::input.inputSelect',['label'=>'Main Menu','key'=>'main_menu','array'=>$arrayMenu,'type'=>'configs'])
			@include('phobrv::input.inputText',['label'=>'Name Web','key'=>'site_name','type'=>'configs'])

		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right">{{__('Submit')}}</button>
		</div>
	</form>
</div>

