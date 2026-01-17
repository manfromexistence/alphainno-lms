<?php

namespace App\View\Composers;

use App\Services\SidebarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Create a new sidebar composer.
     */
    public function __construct(
        protected SidebarService $sidebarService
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $menuItems = [];
        
        if (Auth::check()) {
            $user = Auth::user();
            $menuItems = $this->sidebarService->getMenuItems($user);
        }

        $view->with('sidebarMenuItems', $menuItems);
    }
}
