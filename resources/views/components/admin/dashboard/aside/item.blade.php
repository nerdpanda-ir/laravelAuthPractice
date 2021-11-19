@props(['icon'=>'far fa-link','title' , 'url'=>'#'])
<li><a href="{!! $url !!}" class="collapsible-header waves-effect"><i class="{!! $icon !!}"></i>
        {!! $title !!} @isset($body)<i class="fas fa-angle-down rotate-icon"></i> @endisset </a>
    @isset($body)
        <div class="collapsible-body" style="display: block;">
            <ul>
                {!! $body !!}
            </ul>
        </div>
    @endisset
</li>
