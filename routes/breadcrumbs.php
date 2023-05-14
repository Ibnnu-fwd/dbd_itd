<?php // routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

// Province
Breadcrumbs::for('province', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Provinsi', route('admin.province.index'));
});

// Province > Create
Breadcrumbs::for('province.create', function (BreadcrumbTrail $trail) {
    $trail->parent('province');
    $trail->push('Tambah Provinsi', route('admin.province.create'));
});

// Regency
Breadcrumbs::for('regency', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Kabupaten/Kota', route('admin.regency.index'));
});

// Regency > Create
Breadcrumbs::for('regency.create', function (BreadcrumbTrail $trail) {
    $trail->parent('regency');
    $trail->push('Tambah Kabupaten/Kota', route('admin.regency.create'));
});

// District
Breadcrumbs::for('district', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Kabupaten/Kota', route('admin.district.index'));
});

// District > Create
Breadcrumbs::for('district.create', function (BreadcrumbTrail $trail) {
    $trail->parent('district');
    $trail->push('Tambah Kecamatan', route('admin.district.create'));
});

// Tpa Type
Breadcrumbs::for('tpa-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis TPA', route('admin.tpa-type.index'));
});

// Tpa Type > Create
Breadcrumbs::for('tpa-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('tpa-type');
    $trail->push('Tambah Jenis TPA', route('admin.tpa-type.create'));
});
