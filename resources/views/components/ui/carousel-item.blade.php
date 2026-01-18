@props(['class' => ''])

<div role="group" aria-roledescription="slide" data-carousel-item
     class="min-w-0 shrink-0 grow-0 basis-full pl-4 {{ $class }}">
    {{ $slot }}
</div>
