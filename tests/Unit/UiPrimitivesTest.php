<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

uses(TestCase::class);

it('merender komponen tombol, badge, card, dan alert daisyUI dengan class tema rdm', function () {
    $button = Blade::render('<x-ui.button variant="primary">Simpan Nilai</x-ui.button>');
    $badge = Blade::render('<x-ui.badge variant="success">Terkirim</x-ui.badge>');
    $card = Blade::render('<x-ui.card title="Rekap Kelas">Isi kartu</x-ui.card>');
    $alert = Blade::render('<x-ui.alert variant="info">Periode aktif dibuka</x-ui.alert>');

    expect($button)->toContain('btn')->toContain('btn-primary')->toContain('Simpan Nilai');
    expect($badge)->toContain('badge')->toContain('badge-success')->toContain('Terkirim');
    expect($card)->toContain('card')->toContain('Rekap Kelas')->toContain('Isi kartu');
    expect($alert)->toContain('alert')->toContain('alert-info')->toContain('Periode aktif dibuka');
});

it('merender komponen form dan tabel dengan ukuran kompak', function () {
    $input = Blade::render('<x-ui.input label="Nama Siswa" name="name" />');
    $select = Blade::render('<x-ui.select label="Rombel" name="classroom_id" placeholder="Pilih rombel"><option value="1">VII-A</option></x-ui.select>');
    $table = Blade::render('<x-ui.table><thead><tr><th>Nama</th></tr></thead></x-ui.table>');

    expect($input)->toContain('input-bordered')->toContain('input-sm')->toContain('Nama Siswa');
    expect($select)->toContain('select-bordered')->toContain('select-sm')->toContain('Pilih rombel');
    expect($table)->toContain('table-sm')->toContain('Nama');
});
