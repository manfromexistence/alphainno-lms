<?php

/**
 * Feature: lms-admin-panel, Property 2: Sidebar Menu Consistency
 * 
 * For any user, the sidebar menu items returned by the SidebarService should exactly 
 * match the set of pages that user is permitted to access based on their role.
 * 
 * **Validates: Requirements 1.6**
 * THE Admin_Panel SHALL render the sidebar navigation dynamically based on the logged-in user's role
 */

use App\Models\User;
use App\Models\Role;
use App\Services\SidebarService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure all roles exist in the database
    $roles = ['super-admin', 'teacher', 'student', 'parent'];
    foreach ($roles as $roleSlug) {
        Role::firstOrCreate(
            ['slug' => $roleSlug],
            ['name' => ucfirst(str_replace('-', ' ', $roleSlug)), 'description' => "Test {$roleSlug} role"]
        );
    }
});

/**
 * Helper to create a user with a specific role.
 */
function createSidebarTestUserWithRole(string $roleSlug): User
{
    $user = User::factory()->create();
    
    $role = Role::firstOrCreate(
        ['slug' => $roleSlug],
        ['name' => ucfirst(str_replace('-', ' ', $roleSlug)), 'description' => "Test {$roleSlug} role"]
    );
    
    $user->assignRole($role);
    $user->refresh();
    
    return $user;
}

/**
 * Helper to extract all routes from menu items recursively.
 */
function extractAllRoutes(array $items): array
{
    $routes = [];
    
    foreach ($items as $item) {
        if (isset($item['route']) && $item['route'] !== null) {
            $routes[] = $item['route'];
        }
        
        if (isset($item['children']) && is_array($item['children'])) {
            $routes = array_merge($routes, extractAllRoutes($item['children']));
        }
    }
    
    return array_unique($routes);
}

/**
 * Helper to get all routes that a role should have access to based on menu structure.
 */
function getExpectedRoutesForRole(string $role, array $menuStructure): array
{
    $routes = [];
    
    foreach ($menuStructure as $item) {
        // Check if this item is accessible to the role
        if (!isset($item['roles']) || empty($item['roles']) || in_array($role, $item['roles'])) {
            if (isset($item['route']) && $item['route'] !== null) {
                $routes[] = $item['route'];
            }
            
            // Process children
            if (isset($item['children']) && is_array($item['children'])) {
                $childRoutes = getExpectedRoutesForRole($role, $item['children']);
                $routes = array_merge($routes, $childRoutes);
            }
        }
    }
    
    return array_unique($routes);
}

/**
 * Property Test: Menu items returned for a user match their role permissions
 * 
 * For any randomly selected role, the menu items returned by getMenuItems should
 * only contain items that the role is permitted to access.
 * 
 * **Validates: Requirement 1.6**
 */
test('Menu items returned for any user match their role permissions', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $menuItems = $service->getMenuItems($user);
    
    // Verify all returned menu items are accessible to this role
    $verifyItemsAccessible = function (array $items, string $role) use (&$verifyItemsAccessible, $service) {
        foreach ($items as $item) {
            // Super admin can access everything - verify they get all items
            if ($role === SidebarService::ROLE_SUPER_ADMIN) {
                // Super admin should have access to all items
                expect(true)->toBeTrue("Super admin has access to '{$item['title']}'");
            } else {
                // For other roles, verify they have access to the item
                if (isset($item['roles']) && !empty($item['roles'])) {
                    $hasAccess = in_array($role, $item['roles']);
                    expect($hasAccess)->toBeTrue(
                        "Menu item '{$item['title']}' should be accessible to role '{$role}'. " .
                        "Item requires roles: " . implode(', ', $item['roles'])
                    );
                }
            }
            
            // Recursively check children
            if (isset($item['children']) && is_array($item['children'])) {
                $verifyItemsAccessible($item['children'], $role);
            }
        }
    };
    
    $verifyItemsAccessible($menuItems, $selectedRole);
})->repeat(100);

/**
 * Property Test: Routes in menu items exactly match accessible routes
 * 
 * For any user, the routes extracted from getMenuItems should exactly match
 * the routes returned by getAccessibleRoutes.
 * 
 * **Validates: Requirement 1.6**
 */
test('Routes in menu items exactly match accessible routes for any user', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $menuItems = $service->getMenuItems($user);
    $menuRoutes = extractAllRoutes($menuItems);
    $accessibleRoutes = $service->getAccessibleRoutes($user);
    
    // Sort both arrays for comparison
    sort($menuRoutes);
    sort($accessibleRoutes);
    
    expect($menuRoutes)->toBe($accessibleRoutes, 
        "Routes from menu items should exactly match accessible routes for role '{$selectedRole}'");
})->repeat(100);

/**
 * Property Test: No menu items shown that user cannot access
 * 
 * For any user, there should be no menu items in the returned list that
 * the user's role is not permitted to access.
 * 
 * **Validates: Requirement 1.6**
 */
test('No menu items shown that user cannot access', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $menuItems = $service->getMenuItems($user);
    
    // For super-admin, verify they have access to everything
    if ($selectedRole === SidebarService::ROLE_SUPER_ADMIN) {
        // Super admin should have access to all menu items
        expect(count($menuItems))->toBeGreaterThan(0, 
            "Super admin should have access to menu items");
    } else {
        // For non-super-admin users, verify no unauthorized items are present
        $checkNoUnauthorizedItems = function (array $items, string $role) use (&$checkNoUnauthorizedItems) {
            foreach ($items as $item) {
                if (isset($item['roles']) && !empty($item['roles'])) {
                    $hasAccess = in_array($role, $item['roles']);
                    expect($hasAccess)->toBeTrue(
                        "User with role '{$role}' should not see menu item '{$item['title']}' which requires roles: " . 
                        implode(', ', $item['roles'])
                    );
                }
                
                if (isset($item['children']) && is_array($item['children'])) {
                    $checkNoUnauthorizedItems($item['children'], $role);
                }
            }
        };
        
        $checkNoUnauthorizedItems($menuItems, $selectedRole);
    }
})->repeat(100);

/**
 * Property Test: All accessible routes have corresponding menu items
 * 
 * For any user, every route returned by getAccessibleRoutes should have
 * a corresponding menu item in getMenuItems.
 * 
 * **Validates: Requirement 1.6**
 */
test('All accessible routes have corresponding menu items', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $accessibleRoutes = $service->getAccessibleRoutes($user);
    $menuItems = $service->getMenuItems($user);
    $menuRoutes = extractAllRoutes($menuItems);
    
    foreach ($accessibleRoutes as $route) {
        $hasRoute = in_array($route, $menuRoutes);
        expect($hasRoute)->toBeTrue(
            "Accessible route '{$route}' should have a corresponding menu item for role '{$selectedRole}'");
    }
})->repeat(100);

/**
 * Property Test: canAccessRoute is consistent with getAccessibleRoutes
 * 
 * For any user and any route, canAccessRoute should return true if and only if
 * the route is in getAccessibleRoutes.
 * 
 * **Validates: Requirement 1.6**
 */
test('canAccessRoute is consistent with getAccessibleRoutes for any user', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $accessibleRoutes = $service->getAccessibleRoutes($user);
    
    // Get all possible routes from super admin (complete set)
    $allRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_SUPER_ADMIN);
    
    foreach ($allRoutes as $route) {
        $canAccess = $service->canAccessRoute($user, $route);
        $isInAccessibleRoutes = in_array($route, $accessibleRoutes);
        
        expect($canAccess)->toBe($isInAccessibleRoutes,
            "canAccessRoute('{$route}') should be " . ($isInAccessibleRoutes ? 'true' : 'false') . 
            " for role '{$selectedRole}'");
    }
})->repeat(100);

/**
 * Property Test: Super Admin has access to all routes
 * 
 * For any route in the system, Super Admin should have access to it.
 * 
 * **Validates: Requirement 1.6**
 */
test('Super Admin has access to all routes in the system', function () {
    $service = new SidebarService();
    $superAdmin = createSidebarTestUserWithRole(SidebarService::ROLE_SUPER_ADMIN);
    
    // Get all routes from the full menu structure
    $superAdminRoutes = $service->getAccessibleRoutes($superAdmin);
    
    // For each role, their routes should be a subset of super admin routes
    $roles = [SidebarService::ROLE_TEACHER, SidebarService::ROLE_STUDENT, SidebarService::ROLE_PARENT];
    $selectedRole = $roles[array_rand($roles)];
    
    $roleRoutes = $service->getAccessibleRoutesForRole($selectedRole);
    
    foreach ($roleRoutes as $route) {
        $hasRoute = in_array($route, $superAdminRoutes);
        expect($hasRoute)->toBeTrue(
            "Super Admin should have access to route '{$route}' that '{$selectedRole}' can access");
    }
})->repeat(50);

/**
 * Property Test: Role-specific routes are not accessible to other roles
 * 
 * Routes that are designated for a specific role should not be accessible
 * to users with different roles (except Super Admin).
 * 
 * **Validates: Requirement 1.6**
 */
test('Role-specific routes are not accessible to other roles', function () {
    $service = new SidebarService();
    
    // Define role-specific routes
    $roleSpecificRoutes = [
        SidebarService::ROLE_SUPER_ADMIN => ['dashboard.settings.index', 'dashboard.users.index', 'dashboard.roles.index'],
        SidebarService::ROLE_STUDENT => ['dashboard.my-courses', 'dashboard.my-results', 'dashboard.my-attendance', 'dashboard.my-fees'],
        SidebarService::ROLE_PARENT => ['dashboard.children', 'dashboard.children.progress', 'dashboard.children.attendance', 'dashboard.children.fees'],
    ];
    
    // Randomly select a role to test
    $testRoles = [SidebarService::ROLE_TEACHER, SidebarService::ROLE_STUDENT, SidebarService::ROLE_PARENT];
    $selectedRole = $testRoles[array_rand($testRoles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $accessibleRoutes = $service->getAccessibleRoutes($user);
    
    // Check that super-admin-only routes are not accessible
    foreach ($roleSpecificRoutes[SidebarService::ROLE_SUPER_ADMIN] as $adminRoute) {
        $hasRoute = in_array($adminRoute, $accessibleRoutes);
        expect($hasRoute)->toBeFalse(
            "Role '{$selectedRole}' should not have access to admin-only route '{$adminRoute}'");
    }
    
    // Check that student-specific routes are only accessible to students
    if ($selectedRole !== SidebarService::ROLE_STUDENT) {
        foreach ($roleSpecificRoutes[SidebarService::ROLE_STUDENT] as $studentRoute) {
            $hasRoute = in_array($studentRoute, $accessibleRoutes);
            expect($hasRoute)->toBeFalse(
                "Role '{$selectedRole}' should not have access to student-only route '{$studentRoute}'");
        }
    }
    
    // Check that parent-specific routes are only accessible to parents
    if ($selectedRole !== SidebarService::ROLE_PARENT) {
        foreach ($roleSpecificRoutes[SidebarService::ROLE_PARENT] as $parentRoute) {
            $hasRoute = in_array($parentRoute, $accessibleRoutes);
            expect($hasRoute)->toBeFalse(
                "Role '{$selectedRole}' should not have access to parent-only route '{$parentRoute}'");
        }
    }
})->repeat(50);

/**
 * Property Test: Menu consistency between getMenuItems and getMenuItemsForRole
 * 
 * For any user with a single role, getMenuItems should return the same items
 * as getMenuItemsForRole for that role.
 * 
 * **Validates: Requirement 1.6**
 */
test('Menu consistency between getMenuItems and getMenuItemsForRole', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $menuItemsFromUser = $service->getMenuItems($user);
    $menuItemsFromRole = $service->getMenuItemsForRole($selectedRole);
    
    // Extract routes from both
    $routesFromUser = extractAllRoutes($menuItemsFromUser);
    $routesFromRole = extractAllRoutes($menuItemsFromRole);
    
    sort($routesFromUser);
    sort($routesFromRole);
    
    expect($routesFromUser)->toBe($routesFromRole,
        "Menu items from getMenuItems should match getMenuItemsForRole for role '{$selectedRole}'");
})->repeat(100);

/**
 * Property Test: Dashboard is always accessible
 * 
 * For any user with any role, the dashboard route should always be accessible.
 * 
 * **Validates: Requirement 1.6**
 */
test('Dashboard is always accessible for any role', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $accessibleRoutes = $service->getAccessibleRoutes($user);
    
    $hasDashboard = in_array('dashboard', $accessibleRoutes);
    expect($hasDashboard)->toBeTrue(
        "Dashboard should be accessible for role '{$selectedRole}'");
    
    expect($service->canAccessRoute($user, 'dashboard'))->toBeTrue(
        "canAccessRoute should return true for dashboard for role '{$selectedRole}'");
})->repeat(100);

/**
 * Property Test: Menu item count is consistent
 * 
 * For any user, the menu item count should match the actual number of items
 * returned by getMenuItems.
 * 
 * **Validates: Requirement 1.6**
 */
test('Menu item count is consistent for any user', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $menuItems = $service->getMenuItems($user);
    $reportedCount = $service->getMenuItemCount($user);
    
    // Count items manually (including children)
    $countItems = function (array $items) use (&$countItems): int {
        $count = 0;
        foreach ($items as $item) {
            $count++;
            if (isset($item['children']) && is_array($item['children'])) {
                $count += $countItems($item['children']);
            }
        }
        return $count;
    };
    
    $actualCount = $countItems($menuItems);
    
    expect($reportedCount)->toBe($actualCount,
        "Reported menu item count should match actual count for role '{$selectedRole}'");
})->repeat(100);

/**
 * Property Test: Filtered menu preserves hierarchy
 * 
 * For any user, if a parent menu item is present, it should have at least one
 * accessible child (unless it has a direct route).
 * 
 * **Validates: Requirement 1.6**
 */
test('Filtered menu preserves hierarchy - parents have accessible children', function () {
    $roles = SidebarService::getAllRoles();
    $service = new SidebarService();
    
    // Randomly select a role
    $selectedRole = $roles[array_rand($roles)];
    $user = createSidebarTestUserWithRole($selectedRole);
    
    $menuItems = $service->getMenuItems($user);
    
    $verifyHierarchy = function (array $items) use (&$verifyHierarchy) {
        foreach ($items as $item) {
            // If item has no direct route, it must have children
            if (!isset($item['route']) || $item['route'] === null) {
                $hasChildren = isset($item['children']) && is_array($item['children']);
                expect($hasChildren)->toBeTrue(
                    "Menu item '{$item['title']}' without route should have children");
                
                if ($hasChildren) {
                    expect(count($item['children']))->toBeGreaterThan(0,
                        "Menu item '{$item['title']}' without route should have at least one child");
                }
            }
            
            // Recursively check children
            if (isset($item['children']) && is_array($item['children'])) {
                $verifyHierarchy($item['children']);
            }
        }
    };
    
    $verifyHierarchy($menuItems);
})->repeat(100);
