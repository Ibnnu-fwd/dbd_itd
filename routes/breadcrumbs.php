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
    $trail->push('Kecamatan', route('admin.district.index'));
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

// Tpa Type > Edit
Breadcrumbs::for('tpa-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('tpa-type');
    $trail->push('Edit Jenis TPA', route('admin.tpa-type.edit', $data->id));
});

// Floor Type
Breadcrumbs::for('floor-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Lantai', route('admin.floor-type.index'));
});

// Floor Type > Create
Breadcrumbs::for('floor-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('floor-type');
    $trail->push('Tambah Jenis Lantai', route('admin.floor-type.create'));
});

// Floor Type > Edit
Breadcrumbs::for('floor-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('floor-type');
    $trail->push('Edit Jenis Lantai', route('admin.floor-type.edit', $data->id));
});

// Environment Type
Breadcrumbs::for('environment-type', function(BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Lingkungan', route('admin.environment-type.index'));
});

// Environment Type > Create
Breadcrumbs::for('environment-type.create', function(BreadcrumbTrail $trail) {
    $trail->parent('environment-type');
    $trail->push('Tambah Jenis Lingkungan', route('admin.environment-type.create'));
});

// Environment Type > Edit
Breadcrumbs::for('environment-type.edit', function(BreadcrumbTrail $trail, $data) {
    $trail->parent('environment-type');
    $trail->push('Edit Jenis Lingkungan', route('admin.environment-type.edit', $data->id));
});

// Village
Breadcrumbs::for('village', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Desa', route('admin.village.index'));
});

// Village > Create
Breadcrumbs::for('village.create', function (BreadcrumbTrail $trail) {
    $trail->parent('village');
    $trail->push('Tambah Desa', route('admin.village.create'));
});

// Village > Edit
Breadcrumbs::for('village.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('village');
    $trail->push('Edit Desa', route('admin.village.edit', $data->id));
});

// Location Type
Breadcrumbs::for('location-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Lokasi', route('admin.location-type.index'));
});

// Location Type > Create
Breadcrumbs::for('location-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('location-type');
    $trail->push('Tambah Jenis Lokasi', route('admin.location-type.create'));
});

// Location Type > Edit
Breadcrumbs::for('location-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('location-type');
    $trail->push('Edit Jenis Lokasi', route('admin.location-type.edit', $data->id));
});

// Settlement Type
Breadcrumbs::for('settlement-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Permukiman', route('admin.settlement-type.index'));
});

// Settlement Type > Create
Breadcrumbs::for('settlement-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settlement-type');
    $trail->push('Tambah Jenis Permukiman', route('admin.settlement-type.create'));
});

// Settlement Type > Edit
Breadcrumbs::for('settlement-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('settlement-type');
    $trail->push('Edit Jenis Permukiman', route('admin.settlement-type.edit', $data->id));
});

// Building Type
Breadcrumbs::for('building-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Bangunan', route('admin.building-type.index'));
});

// Building Type > Create
Breadcrumbs::for('building-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('building-type');
    $trail->push('Tambah Jenis Bangunan', route('admin.building-type.create'));
});

// Building Type > Edit
Breadcrumbs::for('building-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('building-type');
    $trail->push('Edit Jenis Bangunan', route('admin.building-type.edit', $data->id));
});
