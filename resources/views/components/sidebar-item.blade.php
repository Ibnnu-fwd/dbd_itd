@props(['title' => '', 'icon' => '', 'route' => '', 'active' => false])

<li>
    <a href="{{ $route }}"
        class="flex items-center px-3 py-3 text-xs 2xl:text-sm text-gray-900 dark:text-white hover:bg-gray-100 {{
            $active ? 'bg-gray-200' : ''
        }}">
        <i
            class="w-3 h-3 {{$active ? 'text-primary' : 'text-gray-500'}} transition duration-75 {{ $icon }}"></i>
        <span class="ml-2">
            {{ $title }}
        </span>
    </a>
</li>
