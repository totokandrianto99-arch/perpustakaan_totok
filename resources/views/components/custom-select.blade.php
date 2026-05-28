@props([
    'name', 'options' => [], 'selected' => '',
    'placeholder' => 'Pilih...', 'icon' => null,
    'searchable' => false, 'width' => '150px'
])
@php
    $normalized = [];
    foreach ($options as $k => $v) {
        $normalized[] = is_array($v) ? $v : ['value' => $k, 'label' => $v];
    }
    $selectedLabel = $placeholder;
    foreach ($normalized as $opt) {
        if ((string)$opt['value'] === (string)$selected && $selected !== '') {
            $selectedLabel = $opt['label']; break;
        }
    }
@endphp
<div class="cs-wrap" style="min-width:{{ $width }}">
    <input type="hidden" name="{{ $name }}" value="{{ $selected }}">
    <div class="cs-trigger {{ $selected !== '' ? 'has-value' : '' }}">
        @if($icon)<i class="fas fa-{{ $icon }} cs-icon"></i>@endif
        <span class="cs-val">{{ $selectedLabel }}</span>
        <i class="fas fa-chevron-down cs-arrow"></i>
    </div>
    <div class="cs-dropdown">
        @if($searchable)
        <div class="cs-search-wrap" style="position:relative;">
            <i class="fas fa-search cs-search-icon"></i>
            <input type="text" class="cs-search" placeholder="Cari...">
        </div>
        @endif
        <div class="cs-list">
            @foreach($normalized as $opt)
            <div class="cs-option {{ (string)$opt['value'] === (string)$selected ? 'selected' : '' }}"
                 data-value="{{ $opt['value'] }}" data-label="{{ $opt['label'] }}">
                @if(!empty($opt['dot']))<span class="cs-dot" style="background:{{ $opt['dot'] }}"></span>@endif
                {{ $opt['label'] }}
                <i class="fas fa-check cs-check"></i>
            </div>
            @endforeach
        </div>
    </div>
</div>
