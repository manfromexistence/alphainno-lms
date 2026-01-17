@props([
    'orientation' => 'horizontal', // 'horizontal' | 'vertical'
    'class' => '',
])

@php
    $carouselId = 'carousel-' . uniqid();
    $isHorizontal = $orientation === 'horizontal';
@endphp

<div id="{{ $carouselId }}" 
     class="relative group {{ $class }}" 
     role="region" 
     aria-roledescription="carousel"
     data-orientation="{{ $orientation }}">

    <!-- Carousel Viewport -->
    <div class="overflow-hidden" data-carousel-viewport>
        <div class="flex {{ $isHorizontal ? '-ml-4' : 'flex-col -mt-4' }}" data-carousel-container>
            {{ $slot }}
        </div>
    </div>

    <!-- Controls -->
    @if($isHorizontal)
        <button type="button" data-carousel-prev
            class="absolute left-4 top-1/2 -translate-y-1/2 h-8 w-8 rounded-full border border-gray-200 bg-white shadow-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity disabled:opacity-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-slate-400 z-10 duration-200"
            disabled>
            <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            <span class="sr-only">Previous slide</span>
        </button>

        <button type="button" data-carousel-next
            class="absolute right-4 top-1/2 -translate-y-1/2 h-8 w-8 rounded-full border border-gray-200 bg-white shadow-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity disabled:opacity-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-slate-400 z-10 duration-200">
            <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="sr-only">Next slide</span>
        </button>
    @else
        <!-- Vertical Controls (Optional implementation) -->
        <button type="button" data-carousel-prev class="...">Up</button>
        <button type="button" data-carousel-next class="...">Down</button>
    @endif
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initCarousel = (root) => {
                if (root.dataset.carouselInitialized) return;

                const viewport = root.querySelector('[data-carousel-viewport]');
                const container = root.querySelector('[data-carousel-container]');
                const prevBtn = root.querySelector('[data-carousel-prev]');
                const nextBtn = root.querySelector('[data-carousel-next]');
                
                if (!viewport || !container) return;

                const isHorizontal = root.dataset.orientation === 'horizontal';
                
                // Scroll Logic
                const scrollNext = () => {
                    const scrollAmount = isHorizontal ? viewport.clientWidth : viewport.clientHeight;
                    viewport.scrollBy({
                        left: isHorizontal ? scrollAmount : 0,
                        top: isHorizontal ? 0 : scrollAmount,
                        behavior: 'smooth'
                    });
                };

                const scrollPrev = () => {
                    const scrollAmount = isHorizontal ? viewport.clientWidth : viewport.clientHeight;
                    viewport.scrollBy({
                        left: isHorizontal ? -scrollAmount : 0,
                        top: isHorizontal ? 0 : -scrollAmount,
                        behavior: 'smooth'
                    });
                };

                const updateButtons = () => {
                    if (!prevBtn || !nextBtn) return;
                    
                    const scrollLeft = viewport.scrollLeft;
                    const scrollWidth = viewport.scrollWidth;
                    const clientWidth = viewport.clientWidth;

                    // Simple tolerance specific logic
                    prevBtn.disabled = scrollLeft <= 1; // Tolerance
                    nextBtn.disabled = scrollLeft + clientWidth >= scrollWidth - 1;
                };

                // Mouse Drag Logic
                let isDown = false;
                let startX;
                let scrollLeft;

                viewport.addEventListener('mousedown', (e) => {
                    isDown = true;
                    viewport.classList.add('cursor-grabbing');
                    viewport.classList.remove('cursor-grab');
                    startX = e.pageX - viewport.offsetLeft;
                    scrollLeft = viewport.scrollLeft;
                });

                viewport.addEventListener('mouseleave', () => {
                    isDown = false;
                    viewport.classList.remove('cursor-grabbing');
                    viewport.classList.add('cursor-grab');
                });

                viewport.addEventListener('mouseup', () => {
                    isDown = false;
                    viewport.classList.remove('cursor-grabbing');
                    viewport.classList.add('cursor-grab');
                });

                viewport.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - viewport.offsetLeft;
                    const walk = (x - startX) * 2; // Scroll-fast
                    viewport.scrollLeft = scrollLeft - walk;
                });
                
                // Add initial grab cursor
                viewport.classList.add('cursor-grab');

                if (prevBtn) prevBtn.addEventListener('click', scrollPrev);
                if (nextBtn) nextBtn.addEventListener('click', scrollNext);
                viewport.addEventListener('scroll', () => {
                    updateButtons();
                });

                // Init state
                updateButtons();
                root.dataset.carouselInitialized = 'true';
            };

            // Init all carousels
            document.querySelectorAll('[role="region"][aria-roledescription="carousel"]').forEach(initCarousel);

            // Observe for new ones (if dynamic)
            const observer = new MutationObserver((mutations) => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) {
                            if (node.hasAttribute('aria-roledescription') && node.getAttribute('aria-roledescription') === 'carousel') {
                                initCarousel(node);
                            } else {
                                node.querySelectorAll('[role="region"][aria-roledescription="carousel"]').forEach(initCarousel);
                            }
                        }
                    });
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
    @endpush
@endonce
