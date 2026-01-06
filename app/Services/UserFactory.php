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
        
        // Gérer les champs optionnels avec des valeurs par défaut
        $createdAt = isset($data['created_at']) && !empty($data['created_at']) 
            ? new DateTime($data['created_at']) 
            : new DateTime();
            
        $lastLogin = isset($data['last_login']) && !empty($data['last_login'])
            ? new DateTime($data['last_login'])
            : null;
        
        if ($role == 'admin') {
            // Pour admin, on utilise le champ "level" pour déterminer si c'est un super admin
            $isSuperAdmin = (isset($data['level']) && $data['level'] == 'Super Admin');
            
            $user = new Admin(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'admin',
                $createdAt,
                $lastLogin,
                $isSuperAdmin
            );
        }
        elseif ($role == 'moderator') {
            $user = new Moderator(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'moderator',
                $createdAt,
                $lastLogin,
                $data['level'] ?? 'junior'
            );
        }
        elseif ($role == 'proUser') {
            $subStart = isset($data['sub_start']) && !empty($data['sub_start'])
                ? new DateTime($data['sub_start'])
                : new DateTime();
                
            $subEnd = isset($data['sub_end']) && !empty($data['sub_end'])
                ? new DateTime($data['sub_end'])
                : null;
            
            $user = new ProUser(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'proUser',
                $createdAt,
                $lastLogin,
                $subStart,
                $subEnd
            );
        }
        else {
            $user = new BasicUser(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['bio'] ?? '',
                'basicUser',
                $createdAt,
                $lastLogin,
                $data['upload_count'] ?? 0
            );
        }
        
        if (isset($data['id']) && method_exists($user, 'setId')) {
            $user->setId((int)$data['id']);
        }
        
        return $user;
    }
}