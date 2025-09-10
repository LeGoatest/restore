<?php

use App\Models\User;
use App\Core\Database;

test('a user can have roles assigned', function () {
    // Find the 'admin' user created by the migrations
    $user = User::findByUsername('admin');
    expect($user)->not->toBeNull();

    $roles = User::getRoles($user['id']);
    expect($roles)->toBeArray()->toContain('Admin');
});

test('a user can have a specific role', function () {
    // Find the 'bob' user who is an 'Editor'
    $user = User::findByUsername('bob');
    expect($user)->not->toBeNull();

    $hasRole = User::hasRole($user['id'], 'Editor');
    expect($hasRole)->toBeTrue();

    $hasAnotherRole = User::hasRole($user['id'], 'Admin');
    expect($hasAnotherRole)->toBeFalse();
});

test('a user has correct permissions from their roles', function () {
    // 'bob' is an editor, so he should have 'PERM_WRITE'
    $user = User::findByUsername('bob');
    expect($user)->not->toBeNull();

    $hasPermission = User::hasPermission($user['id'], 'PERM_WRITE');
    expect($hasPermission)->toBeTrue();

    // 'bob' is an editor, but he should not have 'PERM_DELETE' by default
    $hasPermission = User::hasPermission($user['id'], 'PERM_DELETE');
    expect($hasPermission)->toBeFalse();
});

test('user-specific permissions override role permissions', function () {
    // 'bob' is an editor, and 'PERM_DELETE' is explicitly denied for him
    $userBob = User::findByUsername('bob');
    expect($userBob)->not->toBeNull();
    $hasDelete = User::hasPermission($userBob['id'], 'PERM_DELETE');
    expect($hasDelete)->toBeFalse();

    // 'eve' is an editor, but has been given 'PERM_ADMIN' explicitly
    $userEve = User::findByUsername('eve');
    expect($userEve)->not->toBeNull();
    $hasAdmin = User::hasPermission($userEve['id'], 'PERM_ADMIN');
    expect($hasAdmin)->toBeTrue();
});
