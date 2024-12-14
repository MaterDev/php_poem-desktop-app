<div class="window"
    data-poem-id="{{ $poem->id }}"
    data-x="{{ $poem->window_position_x }}"
    data-y="{{ $poem->window_position_y }}"
    data-width="{{ $poem->window_width }}"
    data-height="{{ $poem->window_height }}">
    <div class="window-controls">
        <div class="window-button close-button"></div>
        <div class="window-button maximize-button"></div>
    </div>
    <div class="window-title">{{$poem->title}}</div>
    <div class="window-content">
        {!! nl2br(e($poem->content)) !!}
    </div>
    <div class="window-resize"></div>
</div>
