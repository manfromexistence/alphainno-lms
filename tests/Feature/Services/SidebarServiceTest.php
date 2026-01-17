<?php

/**
 * Unit Tests for SidebarService
 * 
 * Tests the SidebarService class which provides dynamic navigation
 * based on user roles.
 * 
 * **Validates: Requirements 1.6**
 */

use App\Models\User;
use App\Models\Role;
use App\Services\SidebarService;

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
function createUserWithRole(string $roleSlug): User
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
 * Test: SidebarService can be instantiated
 */
test('SidebarService can be instantiated', function () {
    $service = new SidebarService();
    expect($service)->toBeInstanceOf(SidebarService::class);
});

/**
 * Test: SidebarService returns all roles
 */
test('SidebarService returns all available roles', function () {
    $roles = SidebarService::getAllRoles();
    
    expect($roles)->toBeArray();
    expect($roles)->toContain(SidebarService::ROLE_SUPER_ADMIN);
    expect($roles)->toContain(SidebarService::ROLE_TEACHER);
    expect($roles)->toContain(SidebarService::ROLE_STUDENT);
    expect($roles)->toContain(SidebarService::ROLE_PARENT);
    expect(count($roles))->toBe(4);
});

/**
 * Test: Super Admin sees all menu items
 * 
 * **Validates: Requirement 1.2**
 */
test('Super Admin sees all menu items', function () {
    $user = createUserWithRole('super-admin');
    $service = new SidebarService();
    
    $menuItems = $service->getMenuItems($user);
    
    expect($menuItems)->toBeArray();
    expect(count($menuItems))->toBeGreaterThan(0);
    
    // Super Admin should see all main menu sections
    $titles = array_column($menuItems, 'title');
    expect($titles)->toContain('Dashboard');
    expect($titles)->toContain('Students');
    expect($titles)->toContain('Teachers');
    expect($titles)->toContain('Courses');
    expect($titles)->toContain('Exams');
    expect($titles)->toContain('Payments');
    expect($titles)->toContain('Reports');
    expect($titles)->toContain('Settings');
    expect($titles)->toContain('Users & Roles');
});

/**
 * Test: Teacher sees only teacher-permitted menu items
 * 
 * **Validates: Requirement 1.3**
 */
test('Teacher sees only teacher-permitted menu items', function () {
    $user = createUserWithRole('teacher');
    $service = new SidebarService();
    
    $menuItems = $service->getMenuItems($user);
    
    expect($menuItems)->toBeArray();
    expect(count($menuItems))->toBeGreaterThan(0);
    
    $titles = array_column($menuItems, 'title');
    
    // Teacher should see these
    expect($titles)->toContain('Dashboard');
    expect($titles)->toContain('Students');
    expect($titles)->toContain('Courses');
    expect($titles)->toContain('Exams');
    
    // Teacher should NOT see these (Super Admin only)
    expect($titles)->not->toContain('Teachers');
    expect($titles)->not->toContain('Settings');
    expect($titles)->not->toContain('Users & Roles');
});

/**
 * Test: Student sees only student-permitted menu items
 * 
 * **Validates: Requirement 1.4**
 */
test('Student sees only student-permitted menu items', function () {
    $user = createUserWithRole('student');
    $service = new SidebarService();
    
    $menuItems = $service->getMenuItems($user);
    
    expect($menuItems)->toBeArray();
    expect(count($menuItems))->toBeGreaterThan(0);
    
    $titles = array_column($menuItems, 'title');
    
    // Student should see these
    expect($titles)->toContain('Dashboard');
    expect($titles)->toContain('My Courses');
    expect($titles)->toContain('My Results');
    expect($titles)->toContain('My Attendance');
    expect($titles)->toContain('My Fees');
    
    // Student should NOT see these
    expect($titles)->not->toContain('Teachers');
    expect($titles)->not->toContain('Settings');
    expect($titles)->not->toContain('Users & Roles');
});

/**
 * Test: Parent sees only parent-permitted menu items (read-only)
 * 
 * **Validates: Requirement 1.5**
 */
test('Parent sees only parent-permitted menu items', function () {
    $user = createUserWithRole('parent');
    $service = new SidebarService();
    
    $menuItems = $service->getMenuItems($user);
    
    expect($menuItems)->toBeArray();
    expect(count($menuItems))->toBeGreaterThan(0);
    
    $titles = array_column($menuItems, 'title');
    
    // Parent should see these
    expect($titles)->toContain('Dashboard');
    expect($titles)->toContain('Children Overview');
    expect($titles)->toContain('Academic Progress');
    expect($titles)->toContain('Attendance Records');
    expect($titles)->toContain('Fee Status');
    
    // Parent should NOT see these
    expect($titles)->not->toContain('Teachers');
    expect($titles)->not->toContain('Settings');
    expect($titles)->not->toContain('Users & Roles');
    expect($titles)->not->toContain('Courses');
});

/**
 * Test: Menu items have required structure
 */
test('Menu items have required structure', function () {
    $user = createUserWithRole('super-admin');
    $service = new SidebarService();
    
    $menuItems = $service->getMenuItems($user);
    
    foreach ($menuItems as $item) {
        expect($item)->toHaveKey('title');
        expect($item)->toHaveKey('icon');
        expect($item)->toHaveKey('roles');
        
        expect($item['title'])->toBeString();
        expect($item['icon'])->toBeString();
        expect($item['roles'])->toBeArray();
        
        // Route can be null for parent items with children
        if (isset($item['route'])) {
            expect($item['route'])->toBeString();
        }
        
        // If has children, verify children structure
        if (isset($item['children'])) {
            expect($item['children'])->toBeArray();
            foreach ($item['children'] as $child) {
                expect($child)->toHaveKey('title');
                expect($child)->toHaveKey('icon');
                expect($child)->toHaveKey('route');
                expect($child)->toHaveKey('roles');
            }
        }
    }
});

/**
 * Test: filterByRole correctly filters menu items
 */
test('filterByRole correctly filters menu items', function () {
    $service = new SidebarService();
    
    $items = [
        [
            'title' => 'Admin Only',
            'icon' => 'admin',
            'route' => 'admin.dashboard',
            'roles' => ['super-admin'],
        ],
        [
            'title' => 'Teacher Item',
            'icon' => 'teacher',
            'route' => 'teacher.dashboard',
            'roles' => ['teacher'],
        ],
        [
            'title' => 'Shared Item',
            'icon' => 'shared',
            'route' => 'shared.page',
            'roles' => ['super-admin', 'teacher', 'student'],
        ],
    ];
    
    // Filter for teacher role
    $filtered = $service->filterByRole($items, ['teacher']);
    
    expect(count($filtered))->toBe(2);
    
    $titles = array_column($filtered, 'title');
    expect($titles)->toContain('Teacher Item');
    expect($titles)->toContain('Shared Item');
    expect($titles)->not->toContain('Admin Only');
});

/**
 * Test: filterByRole handles nested children correctly
 */
test('filterByRole handles nested children correctly', function () {
    $service = new SidebarService();
    
    $items = [
        [
            'title' => 'Parent Menu',
            'icon' => 'parent',
            'route' => null,
            'roles' => ['super-admin', 'teacher'],
            'children' => [
                [
                    'title' => 'Admin Child',
                    'icon' => 'admin',
                    'route' => 'admin.child',
                    'roles' => ['super-admin'],
                ],
                [
                    'title' => 'Teacher Child',
                    'icon' => 'teacher',
                    'route' => 'teacher.child',
                    'roles' => ['teacher'],
                ],
            ],
        ],
    ];
    
    // Filter for teacher role
    $filtered = $service->filterByRole($items, ['teacher']);
    
    expect(count($filtered))->toBe(1);
    expect($filtered[0]['title'])->toBe('Parent Menu');
    expect(count($filtered[0]['children']))->toBe(1);
    expect($filtered[0]['children'][0]['title'])->toBe('Teacher Child');
});

/**
 * Test: Parent menu is excluded if no children are accessible
 */
test('Parent menu is excluded if no children are accessible', function () {
    $service = new SidebarService();
    
    $items = [
        [
            'title' => 'Admin Menu',
            'icon' => 'admin',
            'route' => null,
            'roles' => ['super-admin', 'teacher'],
            'children' => [
                [
                    'title' => 'Admin Only Child',
                    'icon' => 'admin',
                    'route' => 'admin.child',
                    'roles' => ['super-admin'],
                ],
            ],
        ],
    ];
    
    // Filter for teacher role - parent has teacher access but no children do
    $filtered = $service->filterByRole($items, ['teacher']);
    
    expect(count($filtered))->toBe(0);
});

/**
 * Test: getMenuItemsForRole returns correct items for each role
 */
test('getMenuItemsForRole returns correct items for each role', function () {
    $service = new SidebarService();
    
    $superAdminItems = $service->getMenuItemsForRole(SidebarService::ROLE_SUPER_ADMIN);
    $teacherItems = $service->getMenuItemsForRole(SidebarService::ROLE_TEACHER);
    $studentItems = $service->getMenuItemsForRole(SidebarService::ROLE_STUDENT);
    $parentItems = $service->getMenuItemsForRole(SidebarService::ROLE_PARENT);
    
    // Super Admin should have the most items
    expect(count($superAdminItems))->toBeGreaterThanOrEqual(count($teacherItems));
    expect(count($superAdminItems))->toBeGreaterThanOrEqual(count($studentItems));
    expect(count($superAdminItems))->toBeGreaterThanOrEqual(count($parentItems));
});

/**
 * Test: getAccessibleRoutes returns all routes for a user
 */
test('getAccessibleRoutes returns all routes for a user', function () {
    $user = createUserWithRole('super-admin');
    $service = new SidebarService();
    
    $routes = $service->getAccessibleRoutes($user);
    
    expect($routes)->toBeArray();
    expect(count($routes))->toBeGreaterThan(0);
    expect($routes)->toContain('dashboard');
    expect($routes)->toContain('dashboard.settings.index');
});

/**
 * Test: getAccessibleRoutesForRole returns correct routes
 */
test('getAccessibleRoutesForRole returns correct routes', function () {
    $service = new SidebarService();
    
    $superAdminRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_SUPER_ADMIN);
    $teacherRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_TEACHER);
    
    // Super Admin should have access to settings
    expect($superAdminRoutes)->toContain('dashboard.settings.index');
    
    // Teacher should NOT have access to settings
    expect($teacherRoutes)->not->toContain('dashboard.settings.index');
});

/**
 * Test: canAccessRoute correctly checks route access
 */
test('canAccessRoute correctly checks route access', function () {
    $superAdmin = createUserWithRole('super-admin');
    $teacher = createUserWithRole('teacher');
    $service = new SidebarService();
    
    // Super Admin can access settings
    expect($service->canAccessRoute($superAdmin, 'dashboard.settings.index'))->toBeTrue();
    
    // Teacher cannot access settings
    expect($service->canAccessRoute($teacher, 'dashboard.settings.index'))->toBeFalse();
    
    // Both can access dashboard
    expect($service->canAccessRoute($superAdmin, 'dashboard'))->toBeTrue();
    expect($service->canAccessRoute($teacher, 'dashboard'))->toBeTrue();
});

/**
 * Test: getMenuItemCount returns correct count
 */
test('getMenuItemCount returns correct count', function () {
    $superAdmin = createUserWithRole('super-admin');
    $student = createUserWithRole('student');
    $service = new SidebarService();
    
    $superAdminCount = $service->getMenuItemCount($superAdmin);
    $studentCount = $service->getMenuItemCount($student);
    
    // Super Admin should have more menu items than student
    expect($superAdminCount)->toBeGreaterThan($studentCount);
});

/**
 * Test: Dashboard is accessible to all roles
 */
test('Dashboard is accessible to all roles', function () {
    $service = new SidebarService();
    
    $roles = SidebarService::getAllRoles();
    
    foreach ($roles as $role) {
        $menuItems = $service->getMenuItemsForRole($role);
        $titles = array_column($menuItems, 'title');
        expect($titles)->toContain('Dashboard');
    }
});

/**
 * Test: Settings is only accessible to Super Admin
 */
test('Settings is only accessible to Super Admin', function () {
    $service = new SidebarService();
    
    $superAdminRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_SUPER_ADMIN);
    $teacherRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_TEACHER);
    $studentRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_STUDENT);
    $parentRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_PARENT);
    
    expect($superAdminRoutes)->toContain('dashboard.settings.index');
    expect($teacherRoutes)->not->toContain('dashboard.settings.index');
    expect($studentRoutes)->not->toContain('dashboard.settings.index');
    expect($parentRoutes)->not->toContain('dashboard.settings.index');
});

/**
 * Test: Users & Roles is only accessible to Super Admin
 */
test('Users & Roles is only accessible to Super Admin', function () {
    $service = new SidebarService();
    
    $superAdminRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_SUPER_ADMIN);
    $teacherRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_TEACHER);
    $studentRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_STUDENT);
    $parentRoutes = $service->getAccessibleRoutesForRole(SidebarService::ROLE_PARENT);
    
    expect($superAdminRoutes)->toContain('dashboard.users.index');
    expect($superAdminRoutes)->toContain('dashboard.roles.index');
    expect($teacherRoutes)->not->toContain('dashboard.users.index');
    expect($studentRoutes)->not->toContain('dashboard.users.index');
    expect($parentRoutes)->not->toContain('dashboard.users.index');
});
