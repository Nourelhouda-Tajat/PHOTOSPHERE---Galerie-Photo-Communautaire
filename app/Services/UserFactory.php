<?php
// app/services/UserFactory.php

require_once __DIR__ . '/../Entities/User.php';
require_once __DIR__ . '/../Entities/Admin.php';
require_once __DIR__ . '/../Entities/BasicUser.php';
require_once __DIR__ . '/../Entities/Moderator.php';
require_once __DIR__ . '/../Entities/ProUser.php';

class UserFactory
{
    public static function createFromData($data)
    {
        $role = $data['role'];
        
        if ($role == 'admin') {
            // Pour admin, on utilise le champ "level" pour déterminer si c'est un super admin
            $isSuperAdmin = ($data['level'] == 'Super Admin');
            
            return new Admin(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'admin',
                new DateTime($data['created_at']),
                $data['last_login'] ? new DateTime($data['last_login']) : null,
                $isSuperAdmin
            );
        }
        
        if ($role == 'moderator') {
            return new Moderator(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'moderator',
                new DateTime($data['created_at']),
                $data['last_login'] ? new DateTime($data['last_login']) : null,
                $data['level'] ?? 'junior'
            );
        }
        
        if ($role == 'proUser') {
            return new ProUser(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'proUser',
                new DateTime($data['created_at']),
                $data['last_login'] ? new DateTime($data['last_login']) : null,
                $data['sub_start'] ? new DateTime($data['sub_start']) : new DateTime(),
                $data['sub_end'] ? new DateTime($data['sub_end']) : null
            );
        }
        
        // Par défaut: basicUser
        return new BasicUser(
            $data['username'],
            $data['email'],
            $data['password'],
            $data['bio'] ?? '',
            'basicUser',
            new DateTime($data['created_at']),
            $data['last_login'] ? new DateTime($data['last_login']) : null,
            $data['upload_count'] ?? 0
        );
    }
}