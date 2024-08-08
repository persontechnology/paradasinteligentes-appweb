<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Inicio', route('dashboard'));
});

// paradas
Breadcrumbs::for('paradas.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Paradas', route('paradas.index'));
});
Breadcrumbs::for('paradas.create', function (BreadcrumbTrail $trail) {
    $trail->parent('paradas.index');
    $trail->push('Nuevo', route('paradas.create'));
});

// rutas
Breadcrumbs::for('rutas.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Rutas', route('rutas.index'));
});

Breadcrumbs::for('rutas.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rutas.index');
    $trail->push('Nuevo', route('rutas.create'));
});
Breadcrumbs::for('rutas.edit', function (BreadcrumbTrail $trail,$model) {
    $trail->parent('rutas.index');
    $trail->push('Editar', route('rutas.edit',$model));
});

// vehiculos
Breadcrumbs::for('vehiculos.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Vehiculos', route('vehiculos.index'));
});

Breadcrumbs::for('vehiculos.create', function (BreadcrumbTrail $trail) {
    $trail->parent('vehiculos.index');
    $trail->push('Nuevo', route('vehiculos.create'));
});
Breadcrumbs::for('vehiculos.edit', function (BreadcrumbTrail $trail,$model) {
    $trail->parent('vehiculos.index');
    $trail->push('Editar', route('vehiculos.edit',$model));
});
Breadcrumbs::for('vehiculos.horario', function (BreadcrumbTrail $trail,$model) {
    $trail->parent('vehiculos.index');
    $trail->push('Horario', route('vehiculos.horario',$model));
});
