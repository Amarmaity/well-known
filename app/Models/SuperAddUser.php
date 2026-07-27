<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class SuperAddUser extends Model
{
    use HasFactory;

    protected $table = 'super_add_users';

    protected $fillable = [
        'fname',
        'lname',
        'dob',
        'gender',
        'mobno',
        'employee_id',
        'evaluation_purpose',
        'division',
        'manager_name',
        'manager_id',
        'admin_id',
        'hr_id',
        'department',
        'designation',
        'user_type',
        'user_roles',
        'salary',
        'email',
        'client_id',
        'salary_grade',
        'password',
        'company_percentage',
        'financial_year',
        'status',
        'probation_date',
        'employee_status'
    ];

    public function getMobnoAttribute($value)
    {
        return $this->decryptSensitiveValue($value);
    }

    public function setMobnoAttribute($value): void
    {
        $this->attributes['mobno'] = $this->encryptSensitiveValue($value);
    }

    public function getSalaryAttribute($value)
    {
        return $this->decryptSensitiveValue($value);
    }

    public function setSalaryAttribute($value): void
    {
        $this->attributes['salary'] = $this->encryptSensitiveValue($value);
    }

    private function encryptSensitiveValue($value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if ($this->canDecrypt($value)) {
            return $value;
        }

        return Crypt::encryptString((string) $value);
    }

    private function decryptSensitiveValue($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }

    private function canDecrypt($value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }

    public function financialData()
    {
        return $this->hasOne(FinancialData::class, 'emp_id', 'employee_id');
    }

    public function manager()
    {
        return $this->belongsTo(SuperAddUser::class, 'manager_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(SuperAddUser::class, 'admin_id');
    }

    public function hr()
    {
        return $this->belongsTo(SuperAddUser::class, 'hr_id');
    }
}
