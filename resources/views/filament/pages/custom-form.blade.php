<x-filament::page>
    <div class="layer-1">
        <div class="title bg-gray-300 p-4 inline-block rounded-t-xl">
            عنوان
            /
            قابل ویرایش
        </div>
        <div class="content p-4 bg-gray-300 rounded-tr-xl rounded-b-xl">
            <div class="layer-2">
                <div class="title bg-gray-500 p-4 inline-block rounded-t-xl">
                    عنوان
                    /
                    قابل ویرایش
                </div>
                <div class="content p-4 bg-gray-500 rounded-tr-xl rounded-b-xl">
                    <div class="layer-3 bg-black p-4">
                        {{ $this->form }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
