<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // 6. Tambahkan 2 Mata Kuliah Lagi dengan Semester Berbeda
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Pemrograman Berbasis Objek',
                'deskripsi' => 'Mempelajari konsep pemrograman berbasis objek menggunakan PHP.',
                'sks' => 3,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Basis Data',
                'deskripsi' => 'Mempelajari perancangan database, SQL, relasi tabel, dan pengelolaan data.',
                'sks' => 3,
                'semester' => 'Ganjil 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        // 7. Tambahkan 1 Mahasiswa Tambahan
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Amelia',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        // 8. Daftarkan Mahasiswa ke Mata Kuliah
        foreach ([$course1, $course2, $course3] as $course) {
            $course->students()->syncWithoutDetaching([
                $mahasiswa1->id => ['status' => 'approved'],
                $mahasiswa2->id => ['status' => 'approved'],
            ]);
        }

        // 9. Tambahkan 2 Tugas
        $assignment1 = Assignment::updateOrCreate(
            [
                'course_id' => $course1->id,
                'judul' => 'Tugas 1 - Pengenalan Laravel',
            ],
            [
                'deskripsi' => 'Buatlah aplikasi sederhana menggunakan framework Laravel.',
                'deadline' => now()->addDays(7),
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            [
                'course_id' => $course2->id,
                'judul' => 'Tugas 1 - Pemrograman Berbasis Objek',
            ],
            [
                'deskripsi' => 'Buatlah program sederhana dengan menerapkan konsep class dan object.',
                'deadline' => now()->addDays(10),
            ]
        );

        // 10. Tambahkan Contoh Data Submission
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'user_id' => $mahasiswa1->id,
            ],
            [
                'jawaban' => 'Saya telah menyelesaikan tugas pengenalan Laravel dan mengumpulkan hasilnya.',
                'submitted_at' => now(),
                'nilai' => 90,
                'catatan_dosen' => 'Pengerjaan sudah baik dan sesuai dengan instruksi.',
            ]
        );
    }
}