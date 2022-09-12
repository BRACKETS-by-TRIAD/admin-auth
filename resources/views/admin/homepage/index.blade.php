@extends('brackets/admin-ui::admin.layout.default')

@section('body')

    <div class="welcome-quote">

	    <blockquote>
		    {{ $quote }}
		    <cite>
			    {{ $quoteAuthor }}
		    </cite>
	    </blockquote>

    </div>

@endsection