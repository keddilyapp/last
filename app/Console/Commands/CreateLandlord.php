<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateLandlord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landlord:create {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the first landlord user for the SaaS platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating Landlord User for SaaS Platform');
        $this->info('========================================');

        // Check if landlord already exists
        if (User::where('is_landlord', true)->exists()) {
            $this->error('A landlord user already exists!');
            return 1;
        }

        // Get user input
        $name = $this->option('name') ?: $this->ask('Enter landlord name');
        $email = $this->option('email') ?: $this->ask('Enter landlord email');
        $password = $this->option('password') ?: $this->secret('Enter landlord password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Create landlord user
        try {
            $landlord = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_landlord' => true,
                'email_verified_at' => now(),
            ]);

            $this->info('Landlord user created successfully!');
            $this->info('Email: ' . $landlord->email);
            $this->info('You can now login at: ' . url('/landlord'));
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create landlord user: ' . $e->getMessage());
            return 1;
        }
    }
} 