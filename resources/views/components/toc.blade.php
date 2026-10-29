@props(['items' => []])

@if(count($items) > 2)
    <nav aria-label="فهرست مطالب" class="my-5 rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4 shadow-sm dark:border-indigo-900/40 dark:bg-indigo-950/30">
        <details open class="group">
            <summary class="flex cursor-pointer select-none items-center gap-2 list-none text-sm font-black text-indigo-900 [&::-webkit-details-marker]:hidden dark:text-indigo-200">
                <svg class="h-4 w-4 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"></path>
                </svg>
                فهرست مطالب
                <svg class="ms-auto h-4 w-4 text-indigo-400 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                </svg>
            </summary>

            <ol class="mt-3 space-y-1.5 border-t border-indigo-100 pt-3 pr-5 text-sm leading-relaxed text-gray-600 dark:border-indigo-900/40 dark:text-gray-300">
                @foreach($items as $item)
                    <li class="marker:font-bold marker:text-indigo-400">
                        <a href="#{{ $item['id'] }}" class="rounded-sm text-indigo-700 transition-colors hover:text-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-100">
                            {{ $item['text'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </details>
    </nav>
@endif
