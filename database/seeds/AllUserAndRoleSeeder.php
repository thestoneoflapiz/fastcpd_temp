<?php

use App\{User, SuperadminPermission};
use Illuminate\Database\Seeder;

class AllUserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'username' => 'zeunee.lo',
            'name' => 'Zeunee Lo',
            'first_name' => 'Zeunee',
            'last_name' => 'Lo',
            'email' => 'levi@enrichapps.com',
            'contact' => '+63 929-814-7870',
            'professions' => json_encode([
                [
                    "id"=>1,
                    "prc_no"=> "PRO-0001-002",
                    "expiration_date"=> "05/25/2021"
                ]
            ]),
            'password' => bcrypt('ipp12345'),
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        User::create([
            'username' => 'user.a',
            'name' => 'FastCPD Developer',
            'first_name' => 'FastCPD',
            'last_name' => 'Developer',
            'email' => 'dev.enrichapps.adm@gmail.com',
            'contact' => '+63 929-814-7870',
            'professions' => json_encode([
                [
                    "id"=>1,
                    "prc_no"=> "PRO-0001-003",
                    "expiration_date"=> "05/25/2021"
                ]
            ]),
            'password' => bcrypt('ipp12345'),
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]); 
        
        User::create([
            'name' => 'Ryuumi Aiko',
            'first_name' => 'Ryuumi',
            'last_name' => 'Aiko',
            'email' => 'l.hernandez.ipp@gmail.com',
            'superadmin' => 'active',
            'contact' => '+63 929-814-7870',
            'password' => bcrypt('ipp12345'),
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        User::create([
            'name' => 'Jun A. Cahilig III',
            'first_name' => 'Jun',
            'middle_name' => 'A.',
            'last_name' => 'Cahilig III',
            'email' => 'm.cahilig.ipp@gmail.com',
            'superadmin' => 'active',
            'contact' => '+63 956-890-9817',
            'password' => bcrypt('ipp12345'),
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        User::create([
            'name' => 'Sherwin Chan',
            'first_name' => 'Sherwin',
            'middle_name' => 'C.',
            'last_name' => 'Chan',
            'email' => 'sherwin@fastcpd.com',
            'superadmin' => 'active',
            'contact' => '+63 9999097525',
            'password' => bcrypt('sherwin123'),
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        SuperadminPermission::create([
            'user_id' => 2,
            'module_name' => 'verification',
            'view' => 1,
            'create' => 1, 
            'edit' => 1,
            'delete' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        SuperadminPermission::create([
            'user_id' => 2,
            'module_name' => 'purchase_setting',
            'view' => 1,
            'create' => 1, 
            'edit' => 1,
            'delete' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        SuperadminPermission::create([
            'user_id' => 2,
            'module_name' => 'report',
            'view' => 1,
            'create' => 1, 
            'edit' => 1,
            'delete' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]); 

        SuperadminPermission::create([
            'user_id' => 2,
            'module_name' => 'user',
            'view' => 1,
            'create' => 1, 
            'edit' => 1,
            'delete' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]); 
    }
}
