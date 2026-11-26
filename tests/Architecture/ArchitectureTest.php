<?php

$moduleDirs = static function (string $subpath): array {
    $pattern = dirname(__DIR__, 2).'/app/Modules/*/'.$subpath;
    $matches = glob($pattern, GLOB_ONLYDIR) ?: [];
    $namespaces = [];

    foreach ($matches as $dir) {
        $relative = str_replace([dirname(__DIR__, 2).'/app/', '/'], ['', '\\'], $dir);
        $namespaces[] = 'App\\'.$relative;
    }

    return $namespaces;
};

arch('tidak ada fungsi debug tertinggal di kode aplikasi')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();

arch('fungsi env() hanya boleh dipakai di folder config')
    ->expect('env')
    ->not->toBeUsedIn('App');

$dbRestrictedTargets = array_merge(
    ['App\Http\Controllers'],
    $moduleDirs('Http/Controllers'),
    $moduleDirs('Livewire'),
);

arch('controller dan livewire tidak memanggil facade DB secara langsung')
    ->expect('Illuminate\Support\Facades\DB')
    ->not->toBeUsedIn($dbRestrictedTargets);

$actionNamespaces = $moduleDirs('Actions');
if ($actionNamespaces !== []) {
    arch('setiap action modul memiliki method execute')
        ->expect($actionNamespaces)
        ->toHaveMethod('execute');
}

$controllerNamespaces = $moduleDirs('Http/Controllers');
if ($controllerNamespaces !== []) {
    arch('setiap controller modul berbentuk single-action invokable')
        ->expect($controllerNamespaces)
        ->toBeInvokable();
}

$requestNamespaces = $moduleDirs('Http/Requests');
if ($requestNamespaces !== []) {
    arch('setiap form request modul mewarisi FormRequest')
        ->expect($requestNamespaces)
        ->toExtend('Illuminate\Foundation\Http\FormRequest');
}
