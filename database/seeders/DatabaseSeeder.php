<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $permission_list = [
            // ['name' => 'show-roles-list'],
            // ['name' => 'show-roles'],
            // ['name' => 'create-roles'],
            // ['name' => 'edit-roles'],
            // ['name' => 'delete-roles'],
            // ['name' => 'show-users-list'],
            // ['name' => 'show-users'],
            // ['name' => 'create-users'],
            // ['name' => 'edit-users'],
            // ['name' => 'delete-users'],
            // ['name' => 'show-courses-list'],
            // ['name' => 'show-courses'],
            // ['name' => 'create-courses'],
            // ['name' => 'edit-courses'],
            // ['name' => 'delete-courses'],
            // ['name' => 'show-instructors-list'],
            // ['name' => 'show-instructors'],
            // ['name' => 'create-instructors'],
            // ['name' => 'edit-instructors'],
            // ['name' => 'delete-instructors'],
            // ['name' => 'show-students-list'],
            // ['name' => 'show-students'],
            // ['name' => 'create-students'],
            // ['name' => 'edit-students'],
            // ['name' => 'delete-students'],
            // ['name' => 'send-notifiction'],
            ['name' => 'upload-material'],
            ['name' => 'download-material'],
            ['name' => 'edit-material'],
            ['name' => 'upload-assignment'],
            ['name' => 'downlode-assignment'],
            ['name' => 'edit-assignment'],
            ['name' => 'add-grade'],
            ['name' => 'edit-grade'],
            ['name' => 'delete-grade'],
            ['name' => 'make-report'],
            
        ];

        DB::table('permissions')->insert($permission_list);

        $instructorRole = DB::Table('roles')->insert(['name' => 'Instructor']);
        $studentRole = DB::Table('roles')->insert(['name' => 'Student']);
        // $adminRole = DB::Table('roles')->insert(['name' => 'Admin']);

        Role::whereName('Instructor')->first()->givePermissionTo([
            "upload-material",
            "edit-material",
            "download-material",
            'upload-assignment',
            'downlode-assignment',
            'edit-assignment',
            'add-grade',
            'edit-grade',
            'delete-grade',
            'make-report'
        ]);
        Role::whereName('Student')->first()->givePermissionTo([
            'upload-assignment',
            "download-material",
            'show-courses'
        ]);
        // Role::whereName('Admin')->first()->givePermissionTo([
        //      'show-roles-list',
        //      'show-roles',
        //      'create-roles',
        //      'edit-roles',
        //      'delete-roles',
        //      'show-users-list',
        //      'show-users',
        //      'create-users',
        //      'edit-users',
        //      'delete-users',
        //      'show-courses-list',
        //      'show-courses',
        //      'create-courses',
        //      'edit-courses',
        //      'delete-courses',
        //      'show-instructors-list',
        //      'show-instructors',
        //      'create-instructors',
        //      'edit-instructors',
        //      'delete-students',        
        //      'delete-instructors',
        //      'show-students-list',
        //      'show-students',
        //      'create-students',
        //      'edit-students',
        //      'send-notifiction'
        // ]);

        

    }
}
