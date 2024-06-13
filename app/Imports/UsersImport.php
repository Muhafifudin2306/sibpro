<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class UsersImport implements ToModel, WithHeadingRow
{

    private $rows = 0;

    public function getRowCount(): int
    {
        return $this->rows;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // $row["note"] = null;
        // $row[13] = null;
        // return dd($row);
        ++$this->rows;
        $uuid = Str::uuid();
        $user = new User([
            'uuid' => $uuid,
            'name' => $row["nama_lengkap"],
            'nis' => $row["nis"],
            'email' => $row["nis"],
            'gender' => $row["gender"],
            'class_id' => $row["kelas"],
            'category_id' => $row["kategori"],
            'password' => $row["password"],
            'user_email' => $row["email"],
            'number' => $row["nomor_wa"],
        ]);

        if ($row["role"] == 1) {
            $studentRole = Role::where('name', 'Superadmin')->first();
            $user->assignRole($studentRole);
        } else if ($row["role"] == 12) {
            $studentRole = Role::where('name', 'Tata Usaha')->first();
            $user->assignRole($studentRole);
        } else {
            $studentRole = Role::where('name', 'Student')->first();
            $user->assignRole($studentRole);
        }



        return $user;
    }
}
