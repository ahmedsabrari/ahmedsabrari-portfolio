<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // إضافة هذا السطر
use Illuminate\Support\Str;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contacts')->insert([
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed.ali@example.com',
                'subject' => 'Inquiry about services',
                'message' => 'I would like to know more about the services you offer. Could you please provide more details?',
                'ip_address' => '192.168.1.1',
                'read_at' => null, // الرسالة لم تُقرأ بعد
                'form_data' => json_encode(['newsletter_subscription' => true]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sara Hussein',
                'email' => 'sara.hussein@example.com',
                'subject' => 'Feedback on the website',
                'message' => 'I had a great experience using the website, but I encountered a small bug on the login page.',
                'ip_address' => '192.168.1.2',
                'read_at' => now(), // الرسالة تمت قراءتها
                'form_data' => json_encode(['browser' => 'Chrome', 'device' => 'Desktop']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mohamed Youssef',
                'email' => 'mohamed.youssef@example.com',
                'subject' => 'Technical support needed',
                'message' => 'I am experiencing issues with my account. Can you please help me reset my password?',
                'ip_address' => '192.168.1.3',
                'read_at' => null, // الرسالة لم تُقرأ بعد
                'form_data' => json_encode(['urgent' => true]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fatima Zahra',
                'email' => 'fatima.zahra@example.com',
                'subject' => 'Partnership Inquiry',
                'message' => 'We are interested in a partnership with your company. Please let us know how we can proceed.',
                'ip_address' => '192.168.1.4',
                'read_at' => now(), // الرسالة تمت قراءتها
                'form_data' => json_encode(['company_name' => 'Tech Partners', 'industry' => 'Software']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
