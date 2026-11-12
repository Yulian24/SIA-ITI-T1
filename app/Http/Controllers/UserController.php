<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private function getUsers()
    {
        return session('demo_users', [
            [
                'id' => 1,
                'name' => 'Administrator SIA',
                'email' => 'admin@iti.ac.id',
                'nim' => '-',
                'role' => 'Administrator',
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'name' => 'Andi Pratama',
                'email' => 'andi@student.iti.ac.id',
                'nim' => '11224001',
                'role' => 'Mahasiswa',
                'status' => 'Aktif'
            ],
            [
                'id' => 3,
                'name' => 'Siti Rahma',
                'email' => 'siti@student.iti.ac.id',
                'nim' => '11224002',
                'role' => 'Mahasiswa',
                'status' => 'Aktif'
            ],
            [
                'id' => 4,
                'name' => 'Budi Santoso',
                'email' => 'budi@iti.ac.id',
                'nim' => 'D-001',
                'role' => 'Dosen',
                'status' => 'Nonaktif'
            ],
        ]);
    }

    private function saveUsers($users)
    {
        session(['demo_users' => array_values($users)]);
    }

    public function index()
    {
        $users = $this->getUsers();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', [
            'user' => null,
            'pageTitle' => 'Tambah Pengguna'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:120',
            'nim' => 'nullable|string|max:30',
            'role' => 'required|in:Administrator,Dosen,Mahasiswa,Staff',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $users = $this->getUsers();

        $data['id'] = count($users)
            ? max(array_column($users, 'id')) + 1
            : 1;

        $users[] = $data;

        $this->saveUsers($users);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = collect($this->getUsers())
            ->firstWhere('id', (int) $id);

        abort_if(!$user, 404);

        return view('users.form', [
            'user' => $user,
            'pageTitle' => 'Edit Pengguna'
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:120',
            'nim' => 'nullable|string|max:30',
            'role' => 'required|in:Administrator,Dosen,Mahasiswa,Staff',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $users = $this->getUsers();
        $found = false;

        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $user = array_merge($user, $data);
                $found = true;
                break;
            }
        }

        unset($user);

        abort_if(!$found, 404);

        $this->saveUsers($users);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $users = array_filter(
            $this->getUsers(),
            fn($user) => $user['id'] != $id
        );

        $this->saveUsers($users);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}