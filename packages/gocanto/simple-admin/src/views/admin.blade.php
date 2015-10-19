<div class="container">
	<h1>Package view :: You have {{ count($users) }} user(s)</h1>
	<div class="col-md-8">
		<ul class="list-group">
			@foreach($users as $user)
				<li class="list-group-item">
					{{ $user->first_name.' '.$user->last_name }}&nbsp;|&nbsp;<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>