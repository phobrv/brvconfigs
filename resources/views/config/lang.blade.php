
@extends('phobrv::layout.app')

@section('header')
<h1>@lang('Config Lang')</h1>
@endsection

@section('content')
<div class="row">
	<div class="col-md-5">
		<div class="box  box-primary">
			<div class="box-header">
				{{__('Add Lang')}}
			</div>
			<form class="form-horizontal" id="formSubmit" method="post" action="{{route('configlang.store')}}">
				<div class="box-body">
					@csrf
					@include('phobrv::input.inputSelect',['label'=>'Select lang','key'=>'lang','array'=>config('langCode.codes')])
				</div>
				<div class="box-footer">
					<button class="pull-right btn btn-primary" type="submit">{{$data['submit_lable'] ?? 'Submit'}}</button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-md-7">
		<div class="box box-primary">
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>STT</th>
							<th>Code</th>
							<th>Name</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@isset($data['langArray'])
						@foreach($data['langArray'] as  $value)
						<tr>
							<td>{{ $loop->index + 1 }}</td>
							<td>
								{{$value}}
							</td>
							<td>{{config('langCode.codes')[$value]}}</td>
							<td align="center">
								@if($loop->index == 0)
								Main Lang
								@else
								<a href="{{route('configlang.changeMainLang',array('lang'=>$value))}}">
									<button class="btn btn-primary">Change</button>
								</a>
								@endif
							</td>
							<td align="center">
								@if($loop->index != 0)
								<a href="{{route('configlang.removeLang',array('lang'=>$value))}}">
									<button class="btn btn-danger">Remove</button>
								</a>
								@endif
							</td>
						</tr>
						@endforeach
						@endisset
					</tbody>

				</table>

			</div>
		</div>
	</div>
</div>

@endsection

@section('styles')

@endsection

@section('scripts')
<script type="text/javascript">
	function destroy(form){
		var anwser =  confirm("Bạn muốn xóa menugroup group này?");
		if(anwser){
			event.preventDefault();
			document.getElementById(form).submit();
		}
	}
</script>
@endsection