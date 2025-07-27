<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str; // Import Str helper for camelCase

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all resources for which we'll create permissions
        $resources = [
            'user',
            'role', // Spatie's own roles
            'permission', // Spatie's own permissions
            'service',
            'doctor',
            'doctor schedule',
            'reservation',
            'prescription item',
            'recipe',
            'doctor note',
            'payment',
            'payment gateway config',
        ];

        // Define standard CRUD operations
        $crudOperations = ['view', 'create', 'edit', 'delete'];

        // Create Permissions dynamically for each resource and operation
        $allPermissions = [];
        foreach ($resources as $resource) {
            // Convert resource name to camelCase for permission naming
            $resourceCamel = Str::camel(Str::singular($resource)); // e.g., 'doctor schedule' -> 'doctorSchedule'

            foreach ($crudOperations as $operation) {
                // Permission name: e.g., 'createService', 'editDoctorSchedule'
                $permissionName = Str::camel($operation . ' ' . $resourceCamel);
                Permission::firstOrCreate(['name' => $permissionName]);
                $allPermissions[] = $permissionName;
            }
            // Add a 'viewAny' permission for listing resources
            $permissionName = Str::camel('view any ' . $resourceCamel); // e.g., 'viewAnyService'
            Permission::firstOrCreate(['name' => $permissionName]);
            $allPermissions[] = $permissionName;
        }

        // Add specific permissions not covered by CRUD, also in camelCase
        Permission::firstOrCreate(['name' => 'approveReservations']);
        Permission::firstOrCreate(['name' => 'completeReservations']);
        Permission::firstOrCreate(['name' => 'payForReservation']);
        Permission::firstOrCreate(['name' => 'viewOwnReservations']);
        Permission::firstOrCreate(['name' => 'viewOwnRecipes']);
        Permission::firstOrCreate(['name' => 'viewOwnDoctorNotes']);
        Permission::firstOrCreate(['name' => 'viewAllPatients']);
        Permission::firstOrCreate(['name' => 'inputRecipe']);
        Permission::firstOrCreate(['name' => 'inputDoctorNote']);

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign Permissions to Roles

        // Admin: Can do everything
        $adminRole->givePermissionTo(Permission::all());

        // Staff: Can manage most clinic operations, but not core user/role management or payment gateway config
        $staffPermissions = [
            'viewAnyService', 'viewService', 'createService', 'editService', 'deleteService',
            'viewAnyDoctor', 'viewDoctor', 'createDoctor', 'editDoctor', 'deleteDoctor',
            'viewAnyDoctorSchedule', 'viewDoctorSchedule', 'createDoctorSchedule', 'editDoctorSchedule', 'deleteDoctorSchedule',
            'viewAnyReservation', 'viewReservation', 'editReservation', // Staff can edit status
            'approveReservations', 'completeReservations',
            'viewAnyPayment', 'viewPayment', // Staff can view payments
            'viewAnyDoctorNote', 'viewDoctorNote', // Staff can view doctor notes
        ];
        $staffRole->givePermissionTo($staffPermissions);

        // Doctor: Can view their own reservations, input recipes and notes, view patients
        $doctorPermissions = [
            'viewReservation', // Can view all reservations to find their own
            'viewOwnReservations', // Specific permission for user journey
            'createRecipe', // Alias for create/edit recipe
            'createDoctorNote', // Alias for create/edit doctor note
            'viewOwnRecipes', // Specific permission for user journey
            'viewOwnDoctorNotes', // Specific permission for user journey
        ];
        $doctorRole->givePermissionTo($doctorPermissions);

        // User: Can create and view their own reservations, pay, view their own recipes/notes
        $userPermissions = [
            'createReservation',
            'viewAnyReservation', 'viewReservation', // To see their own reservations
            'payForReservation',
            'viewAnyRecipe', 'viewRecipe', // To view recipes (limited to their own via policy)
            'viewAnyDoctorNote', 'viewDoctorNote', // To view doctor notes (limited to their own via policy)
            'viewOwnReservations',
            'viewOwnRecipes',
            'viewOwnDoctorNotes',
        ];
        $userRole->givePermissionTo($userPermissions);


        // Create users and assign roles
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        // Add the special admin user
        $specialAdminUser = User::firstOrCreate(
            ['email' => 'nharits74@gmail.com'],
            [
                'name' => 'Nharits Admin',
                'password' => Hash::make('password'), // Set a default password, user can change it
                'email_verified_at' => now(),
            ]
        );
        $specialAdminUser->assignRole('admin');


        $staffUser = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $staffUser->assignRole('staff');

        $doctor1User = User::firstOrCreate(
            ['email' => 'doctor1@example.com'],
            [
                'name' => 'Dr. Alice Smith',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $doctor1User->assignRole('doctor');

        $doctor2User = User::firstOrCreate(
            ['email' => 'doctor2@example.com'],
            [
                'name' => 'Dr. Bob Johnson',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $doctor2User->assignRole('doctor');

        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $regularUser->assignRole('user');

        // Create 10 additional random users and assign 'user' role
        // User::factory()->count(10)->create()->each(function ($user) {
        //     $user->assignRole('user');
        // });
    }
}

