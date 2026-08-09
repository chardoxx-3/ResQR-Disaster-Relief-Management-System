<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
protected $allowedFields    = [
    'username', 
    'password', 
    'email',
    'last_name',
    'first_name',
    'middle_name',
    'suffix',
    'birthdate',
    'age',
    'birthplace',
    'sex',
    'house_no',
    'street',
    'subdivision',
    'barangay',
    'city',
    'province',
    'zip_code',
    'contact_number',
    'position',              // Changed from 'assigned_barangay'
    'full_name',
    'role', 
    'status'
];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Role constants for easier management
     */
    const ROLE_ADMIN       = 'admin';
    const ROLE_DISTRIBUTOR = 'distributor';
    const ROLE_BARANGAY    = 'barangay';
}