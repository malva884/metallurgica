<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $role = $this->request->get('role');
        $status = $this->request->get('status');
        $username = $this->request->get('username');
        $email = $this->request->get('email');

        return DB::table('users')->select('*', 'name as role')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->Where(function ($query) use ($email, $username, $status, $role) {
                if ($role) {
                    $query->Where('roles.name', '=', $role);
                }
                if ($status) {
                    $value = 1;
                    if ($status != 'Active')
                        $value = 0;
                    $query->Where('users.status', '=', $value);
                }
                if ($email) {
                    $query->Where('email', 'like', '%' . $email . '%');
                }
                if ($username) {
                    $query->Where('firstname', 'like', '%' . $username . '%')
                        ->orWhere('lastname', 'like', '%' . $username . '%');
                }
            })
            ->get();
    }


    public function headings(): array
    {
        return [
            'Id',
            'Nome',
            'Cognome',
            'Email',
            'Vat',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->firstname,
            $user->lastname,
            $user->email,
            $user->vat,
        ];
    }

}
