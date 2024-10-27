@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="text-gray-500">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <a href="javascript:void(0);" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="text-blue-500 hover:underline">
                            {!! __('pagination.previous') !!}
                        </a>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <a href="javascript:void(0);" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="ml-3 text-blue-500 hover:underline">
                            {!! __('pagination.next') !!}
                        </a>
                    @else
                        <span class="text-gray-500 ml-3">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        <span>Menampilkan</span>
                        <span>{{ $paginator->firstItem() }}</span>
                        <span>sampai</span>
                        <span>{{ $paginator->lastItem() }}</span>
                        <span>dari</span>
                        <span>{{ $paginator->total() }}</span>
                        <span>data</span>
                    </p>
                </div>

                <div>
                    <span class="inline-flex rtl:flex-row-reverse">
                        <div class="mr-3">
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" class="text-gray-500">
                                    &laquo;
                                </span>
                            @else
                                <a href="javascript:void(0);" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="text-blue-500 hover:underline" aria-label="&laquo;">
                                    &laquo;
                                </a>
                            @endif
                        </div>

                        @foreach ($elements as $element)
                            @if (is_string($element))
                                <span aria-disabled="true" class="text-gray-500 mx-1">
                                    {{ $element }}
                                </span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page" class="text-blue-500 font-bold mx-1">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="javascript:void(0);" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="text-blue-500 hover:underline mx-1" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        <div class="ml-3">
                            @if ($paginator->hasMorePages())
                                <a href="javascript:void(0);" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="text-blue-500 hover:underline ml-1" aria-label="&raquo;">
                                    &raquo;
                                </a>
                            @else
                                <span aria-disabled="true" class="text-gray-500 ml-1">
                                    &raquo;
                                </span>
                            @endif
                        </div>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
