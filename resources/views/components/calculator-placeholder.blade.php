<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    {{-- :label="$getLabel()"
    :label-sr-only="$isLabelHidden()" --}}
    :helper-text="$getHelperText()"
    {{-- :hint="$getHint()" --}}
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :state-path="$getStatePath()"
>
    <div
        {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-placeholder-component']) }}
        style="margin-top:-10px;text-align:right;">
        {{ $getLabel() }}
        <b style="margin-left: 15px">{!! $getContent() !!}</b>
    </div>

    @if($getHint() != "removeBorder")
        <div style="height: 1px;border:1px dashed #ccc;margin-bottom: -10px;"></div>
    @endif
</x-dynamic-component>
