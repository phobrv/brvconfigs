@extends('phobrv::layout.app')

@section('header')
<h1>@lang('Disable Sidebar Menu')</h1>
@endsection

@section('content')
<div class='box box-primary'>
	<div class="box-body">
		<form class="form-horizontal" method="post" action="{{ route('config.updateSidebarConfig') }}">
			@csrf
			@if( count( config('sidebar.menu') ) > 0 )
			@foreach( config('sidebar.menu') as $menu )
			@php
				$checked = (in_array($menu['id'],$data['sidebar_disable']))  ? 'checked' : '';
			@endphp
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label"> {{ $menu['title'] }}  </label>
				<div class="col-sm-10">
					<input type="checkbox" name="disable[]" value="{{ $menu['id'] }}" {{ $checked }} >
				</div>
			</div>
			@if( $menu['children'] )
			@foreach( $menu['children'] as $submenu )
			@php
				$checked = (in_array($submenu['id'],$data['sidebar_disable']))  ? 'checked' : '';
			@endphp
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label"> {{ $submenu['title'] }} </label>
				<div class="col-sm-9">
					<input type="checkbox" name="disable[]" value="{{ $submenu['id'] }}" {{ $checked }}>
				</div>
			</div>
			@endforeach
			@endif
			@endforeach
			@endif
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</form>
	</div>
</div>
@endsection

@section('styles')

@endsection

@section('scripts')
@include('phobrv::config.jsConfigForm')
@endsection