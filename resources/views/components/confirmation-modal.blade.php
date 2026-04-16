@props(['id' => null, 'maxWidth' => null])

<style>
    :root { --tx:#1a1530; --mu:#7c72a0; --sf:#fff; --sf2:#f8f6fd; }
    [data-theme="dark"] { --tx:#e8e2f5; --mu:#8a7faa; --sf:#161222; --sf2:#1e1830; }
    .bg-white { background-color: var(--sf) !important; }
    .bg-gray-100 { background-color: var(--sf2) !important; }
    .text-gray-900, .text-gray-600, .text-lg, .font-medium { color: var(--tx) !important; }
    .text-red-600 { color: #dc2626 !important; }
</style>

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4" style="background-color: var(--sf);">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
                <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                <h3 class="text-lg font-medium" style="color: var(--tx);">
                    {{ $title }}
                </h3>

                <div class="mt-4 text-sm" style="color: var(--mu);">
                    {{ $content }}
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 text-end" style="background-color: var(--sf2);">
        {{ $footer }}
    </div>
</x-modal>
