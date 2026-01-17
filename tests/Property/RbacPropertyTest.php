<?php

/**
 * Feature: lms-admin-panel, Property 1: Role-Based Page Access
 * 
 * For any user with a specific role and for any page in the system, the user should only 
 * be able to access pages that are permitted for their role. Super_Admin should access 
 * all pages, while Teacher, Student, and Parent should only access their designated pages.
 * 
 * **Validates: Requirements 1.2, 1.3, 1.4, 1.5**
 */

use App\Models\User;
use App\Models\Role;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
function createTestUserWithRole(string $roleSlug): User
{
    $user = User::factory()->create();
    
    $role = Role::firstOrCreate(
        ['slug' => $roleSlug],
        ['name' => ucfirst(str_replace('-', ' ', $roleSlug)), 'description' => "Test {$roleSlug} role"]
    );
    
    $user->assignRole($role);
    
    return $user;
}

/**
 * Helper to create a mock request with an authenticated user.
 */
function createTestAuthenticatedRequest(User $user): Request
{
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);
    return $request;
}


/**
 * Property Test: Super Admin can access all pages through RoleMiddleware
 * 
 * **Validates: Requirement 1.2**
 * WHEN a Super_Admin logs in, THE Admin_Panel SHALL grant full access to all pages and features
 */
test('Super Admin passes RoleMiddleware for any role requirement', function () {
    $user = createTestUserWithRole('super-admin');
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // Test with various role requirements - Super Admin should pass all
    $roleRequirements = ['teacher', 'student', 'parent', 'admin', 'super-admin'];
    
    foreach ($roleRequirements as $requiredRole) {
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, $requiredRole);
        
        expect($response->getStatusCode())
            ->toBe(200, "Super Admin should pass middleware requiring '{$requiredRole}' role");
    }
})->repeat(10);

/**
 * Property Test: Super Admin passes RoleMiddleware with multiple role requirements
 * 
 * **Validates: Requirement 1.2**
 */
test('Super Admin passes RoleMiddleware with multiple role requirements', function () {
    $user = createTestUserWithRole('super-admin');
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // Test with multiple roles - Super Admin should pass
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher', 'student');
    
    expect($response->getStatusCode())->toBe(200);
})->repeat(10);

/**
 * Property Test: Teacher can only access teacher-permitted routes
 * 
 * **Validates: Requirement 1.3**
 * WHEN a Teacher logs in, THE Admin_Panel SHALL display only teacher-related pages
 */
test('Teacher passes RoleMiddleware only for teacher role', function () {
    $user = createTestUserWithRole('teacher');
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // Teacher should pass when teacher role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher');
    
    expect($response->getStatusCode())->toBe(200, "Teacher should pass middleware requiring 'teacher' role");
    
    // Teacher should fail when super-admin role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'super-admin');
    
    expect($response->getStatusCode())->toBe(302, "Teacher should be redirected when 'super-admin' role is required");
})->repeat(10);


/**
 * Property Test: Student can only access student-permitted routes
 * 
 * **Validates: Requirement 1.4**
 * WHEN a Student logs in, THE Admin_Panel SHALL display only student dashboard pages
 */
test('Student passes RoleMiddleware only for student role', function () {
    $user = createTestUserWithRole('student');
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // Student should pass when student role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'student');
    
    expect($response->getStatusCode())->toBe(200, "Student should pass middleware requiring 'student' role");
    
    // Student should fail when teacher role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher');
    
    expect($response->getStatusCode())->toBe(302, "Student should be redirected when 'teacher' role is required");
    
    // Student should fail when super-admin role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'super-admin');
    
    expect($response->getStatusCode())->toBe(302, "Student should be redirected when 'super-admin' role is required");
})->repeat(10);

/**
 * Property Test: Parent can only access parent-permitted routes (read-only)
 * 
 * **Validates: Requirement 1.5**
 * WHEN a Parent logs in, THE Admin_Panel SHALL display read-only access to their children's academic data
 */
test('Parent passes RoleMiddleware only for parent role', function () {
    $user = createTestUserWithRole('parent');
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // Parent should pass when parent role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'parent');
    
    expect($response->getStatusCode())->toBe(200, "Parent should pass middleware requiring 'parent' role");
    
    // Parent should fail when teacher role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher');
    
    expect($response->getStatusCode())->toBe(302, "Parent should be redirected when 'teacher' role is required");
    
    // Parent should fail when super-admin role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'super-admin');
    
    expect($response->getStatusCode())->toBe(302, "Parent should be redirected when 'super-admin' role is required");
})->repeat(10);


/**
 * Property Test: Random role-middleware access verification
 * 
 * For any randomly selected user with a specific role and for any randomly selected 
 * role requirement, the middleware should correctly allow or deny access.
 * 
 * **Validates: Requirements 1.2, 1.3, 1.4, 1.5**
 */
test('Random role-middleware access matches expected behavior', function () {
    $roles = ['super-admin', 'teacher', 'student', 'parent'];
    $middleware = new RoleMiddleware();
    
    // Randomly select a user role
    $userRole = $roles[array_rand($roles)];
    $user = createTestUserWithRole($userRole);
    $request = createTestAuthenticatedRequest($user);
    
    // Randomly select a required role
    $requiredRole = $roles[array_rand($roles)];
    
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, $requiredRole);
    
    if ($userRole === 'super-admin') {
        // Super Admin should always pass
        expect($response->getStatusCode())
            ->toBe(200, "Super Admin should pass middleware requiring '{$requiredRole}' role");
    } elseif ($userRole === $requiredRole) {
        // User with matching role should pass
        expect($response->getStatusCode())
            ->toBe(200, "User with '{$userRole}' role should pass middleware requiring '{$requiredRole}' role");
    } else {
        // User without matching role should be redirected
        expect($response->getStatusCode())
            ->toBe(302, "User with '{$userRole}' role should be redirected when '{$requiredRole}' role is required");
    }
})->repeat(100);

/**
 * Property Test: Unauthenticated users are redirected to login
 * 
 * For any role requirement, an unauthenticated user should be redirected to login.
 */
test('Unauthenticated users are redirected to login by RoleMiddleware', function () {
    $request = Request::create('/test', 'GET');
    // No user resolver set - simulates unauthenticated request
    $request->setUserResolver(fn() => null);
    
    $middleware = new RoleMiddleware();
    
    $roles = ['super-admin', 'teacher', 'student', 'parent'];
    $requiredRole = $roles[array_rand($roles)];
    
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, $requiredRole);
    
    expect($response->getStatusCode())->toBe(302);
    expect($response->headers->get('Location'))->toContain('login');
})->repeat(10);


/**
 * Property Test: Role assignment immediately affects middleware access
 * 
 * When a user is assigned a role, their access permissions should be immediately effective.
 * 
 * **Validates: Requirements 1.2, 1.3, 1.4, 1.5**
 */
test('Role assignment immediately affects middleware access', function () {
    // Create a user without any role
    $user = User::factory()->create();
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // User without role should not pass middleware requiring teacher role
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher');
    
    expect($response->getStatusCode())->toBe(302, "User without role should be redirected");
    
    // Assign teacher role
    $teacherRole = Role::firstOrCreate(
        ['slug' => 'teacher'],
        ['name' => 'Teacher', 'description' => 'Teacher role']
    );
    $user->assignRole($teacherRole);
    
    // Refresh user to get updated roles
    $user->refresh();
    
    // Create new request with refreshed user
    $request = createTestAuthenticatedRequest($user);
    
    // Now user should pass middleware requiring teacher role
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher');
    
    expect($response->getStatusCode())->toBe(200, "User with teacher role should pass middleware");
})->repeat(10);

/**
 * Property Test: Multiple roles grant combined access
 * 
 * A user with multiple roles should pass middleware when any of their roles is required.
 */
test('User with multiple roles passes middleware for any of their roles', function () {
    $user = User::factory()->create();
    
    // Assign both teacher and student roles
    $teacherRole = Role::firstOrCreate(
        ['slug' => 'teacher'],
        ['name' => 'Teacher', 'description' => 'Teacher role']
    );
    $studentRole = Role::firstOrCreate(
        ['slug' => 'student'],
        ['name' => 'Student', 'description' => 'Student role']
    );
    
    $user->assignRole($teacherRole);
    $user->assignRole($studentRole);
    $user->refresh();
    
    $request = createTestAuthenticatedRequest($user);
    $middleware = new RoleMiddleware();
    
    // User should pass when teacher role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher');
    
    expect($response->getStatusCode())->toBe(200, "User with teacher+student roles should pass middleware requiring 'teacher'");
    
    // User should pass when student role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'student');
    
    expect($response->getStatusCode())->toBe(200, "User with teacher+student roles should pass middleware requiring 'student'");
    
    // User should pass when either teacher or parent role is required
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'teacher', 'parent');
    
    expect($response->getStatusCode())->toBe(200, "User with teacher+student roles should pass middleware requiring 'teacher' or 'parent'");
})->repeat(10);


/**
 * Property Test: Middleware with no role requirement allows all authenticated users
 * 
 * When no role is specified in the middleware, all authenticated users should pass.
 */
test('Middleware with no role requirement allows all authenticated users', function () {
    $roles = ['super-admin', 'teacher', 'student', 'parent'];
    $middleware = new RoleMiddleware();
    
    foreach ($roles as $roleSlug) {
        $user = createTestUserWithRole($roleSlug);
        $request = createTestAuthenticatedRequest($user);
        
        // No role requirement - should pass
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });
        
        expect($response->getStatusCode())
            ->toBe(200, "User with '{$roleSlug}' role should pass middleware with no role requirement");
    }
})->repeat(5);

/**
 * Property Test: JSON requests receive JSON error response
 * 
 * When a user without permission makes a JSON request, they should receive a JSON error response.
 */
test('JSON requests receive JSON error response from RoleMiddleware', function () {
    $user = createTestUserWithRole('student');
    
    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);
    $request->headers->set('Accept', 'application/json');
    
    $middleware = new RoleMiddleware();
    
    $response = $middleware->handle($request, function ($req) {
        return new Response('OK', 200);
    }, 'super-admin');
    
    expect($response->getStatusCode())->toBe(403);
    
    $content = json_decode($response->getContent(), true);
    expect($content)->toHaveKey('success');
    expect($content['success'])->toBeFalse();
    expect($content)->toHaveKey('message');
    expect($content)->toHaveKey('code');
    expect($content['code'])->toBe('UNAUTHORIZED_ROLE');
})->repeat(5);
