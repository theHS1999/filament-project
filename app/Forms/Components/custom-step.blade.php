<div
    aria-labelledby="{{ $getId() }}"
    id="{{ $getId() }}"
    x-ref="step-{{ $getId() }}"
    role="tabpanel"
    tabindex="0"
    x-bind:class="{ 'invisible h-0 overflow-y-hidden': step !== @js($getId()) }"
    x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        if (! isStepAccessible(step, @js($getId()))) {
            return
        }

        step = @js($getId())

        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(
            () =>
                $el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'start',
                }),
            200,
        )
    "
    {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-wizard-component-step outline-none']) }}
    >
        <div style="width: 100%;height: 100%;position:relative;">
            <div style="background-color: rgba(67, 67, 67, 0.6);width:100%;height:100%;position:absolute;z-index:10;border-radius: 5px;">
            </div>

            <div style="position: absolute;top:5%;left:0;right:0;z-index:11;text-align: center;color:#666;background: #fff;width: 95%;margin:0 auto;border-radius: 5px;padding: 20px 0;">
                <h3 style="font-size: 24px;width:70%;margin: 0 auto;font-weight: bold;">Your quotation is sent to the Client</h3>
                <ol style="list-style: decimal;text-align:justify;display:inline-block;padding: 20px;">
                    <span style="margin-left: -15px;font-size: 18px;">Next steps :</span>
                    <li>the Client immediately agrees or changes the quote and sends it back for approval</li>
                    <li>contractor can also immediately agree or edit and return it back for approval</li>
                    <li>After approval and digital signature of Client and Contractor</li>
                    <li>the next step (Create project) will be activated and Contractor can create your a project</li>
                </ol>
            </div>

            <div style="padding:20px">{{ $getChildComponentContainer() }}</div>
        </div>
</div>
