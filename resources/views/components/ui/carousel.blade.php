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
            class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 h-10 w-10 md:h-8 md:w-8 rounded-full border border-gray-200 bg-white shadow-lg flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity disabled:opacity-30 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-slate-400 z-10 duration-200"
            disabled>
            <svg class="w-5 h-5 md:w-4 md:h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            <span class="sr-only">Previous slide</span>
        </button>

        <button type="button" data-carousel-next
            class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 h-10 w-10 md:h-8 md:w-8 rounded-full border border-gray-200 bg-white shadow-lg flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity disabled:opacity-30 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-slate-400 z-10 duration-200">
            <svg class="w-5 h-5 md:w-4 md:h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
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
                
                // Get all carousel items
                const items = Array.from(container.querySelectorAll('[data-carousel-item]'));
                let currentIndex = 0;

                // Calculate visible items count
                const getVisibleItemsCount = () => {
                    if (items.length === 0) return 1;
                    const itemWidth = items[0].offsetWidth;
                    const viewportWidth = viewport.clientWidth;
                    return Math.max(1, Math.floor(viewportWidth / itemWidth));
                };

                // Scroll Logic - Snap to full items
                const scrollNext = () => {
                    if (items.length === 0) return;
                    
                    const visibleItems = getVisibleItemsCount();
                    const maxIndex = Math.max(0, items.length - visibleItems);
                    
                    // Move to next item
                    currentIndex = Math.min(currentIndex + 1, maxIndex);
                    
                    // Scroll to the item
                    const targetItem = items[currentIndex];
                    if (targetItem) {
                        const scrollPosition = targetItem.offsetLeft - parseInt(getComputedStyle(container).marginLeft || 0);
                        viewport.scrollTo({
                            left: scrollPosition,
                            behavior: 'smooth'
                        });
                    }
                    
                    updateButtons();
                };

                const scrollPrev = () => {
                    if (items.length === 0) return;
                    
                    // Move to previous item
                    currentIndex = Math.max(currentIndex - 1, 0);
                    
                    // Scroll to the item
                    const targetItem = items[currentIndex];
                    if (targetItem) {
                        const scrollPosition = targetItem.offsetLeft - parseInt(getComputedStyle(container).marginLeft || 0);
                        viewport.scrollTo({
                            left: scrollPosition,
                            behavior: 'smooth'
                        });
                    }
                    
                    updateButtons();
                };

                const updateButtons = () => {
                    if (!prevBtn || !nextBtn || items.length === 0) return;
                    
                    const visibleItems = getVisibleItemsCount();
                    const maxIndex = Math.max(0, items.length - visibleItems);
                    
                    // Update button states
                    prevBtn.disabled = currentIndex <= 0;
                    nextBtn.disabled = currentIndex >= maxIndex;
                    
                    // Show/hide buttons based on whether scrolling is needed
                    if (items.length <= visibleItems) {
                        prevBtn.style.display = 'none';
                        nextBtn.style.display = 'none';
                    } else {
                        prevBtn.style.display = 'flex';
                        nextBtn.style.display = 'flex';
                    }
                };

                // Update current index based on scroll position
                const updateIndexFromScroll = () => {
                    if (items.length === 0) return;
                    
                    const scrollLeft = viewport.scrollLeft;
                    const itemWidth = items[0].offsetWidth;
                    const newIndex = Math.round(scrollLeft / itemWidth);
                    currentIndex = Math.max(0, Math.min(newIndex, items.length - 1));
                    updateButtons();
                };

                // Snap to nearest item on scroll end
                let scrollTimeout;
                viewport.addEventListener('scroll', () => {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(() => {
                        updateIndexFromScroll();
                        
                        // Snap to current item
                        const targetItem = items[currentIndex];
                        if (targetItem) {
                            const scrollPosition = targetItem.offsetLeft - parseInt(getComputedStyle(container).marginLeft || 0);
                            if (Math.abs(viewport.scrollLeft - scrollPosition) > 5) {
                                viewport.scrollTo({
                                    left: scrollPosition,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }, 150);
                });

                // Button click handlers
                if (prevBtn) prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    scrollPrev();
                });
                
                if (nextBtn) nextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    scrollNext();
                });

                // Handle window resize
                let resizeTimeout;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        updateButtons();
                    }, 200);
                });

                // Init state
                setTimeout(() => {
                    updateButtons();
                }, 100);
                
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
