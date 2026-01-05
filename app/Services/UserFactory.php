<?php
class UserFactory
{
    public static function createUser($role, array $data = [])
    {
        $id = $data['id'] ?? null;
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $bio = $data['bio'] ?? '';
        $level = $data['level'] ?? null;
        $subStart = $data['sub_start'] ?? null;
        $subEnd = $data['sub_end'] ?? null;
        
        switch ($role) {
            case 'basicUser':
                return new BasicUser($id, $username, $email, $password, $bio);
                
            case 'proUser':
                return new ProUser($id, $username, $email, $password, $bio, $subStart, $subEnd);
                
            case 'moderator':
                return new Moderator($id, $username, $email, $password, $level);
                
            case 'admin':
                return new Admin($id, $username, $email, $password);
                
            default:
                throw new \InvalidArgumentException("Rôle invalide: $role");
        }
    }
    
    
    
    public static function getAvailableRoles()
    {
        return [
            'basicUser' => 'Utilisateur Basique',
            'proUser' => 'Utilisateur Professionnel',
            'moderator' => 'Modérateur',
            'admin' => 'Administrateur'
        ];
    }
    
    public static function hasPrivilege($role, $privilege)
    {
        $user = self::createUser($role);
        
        switch ($privilege) {
            case 'createPrivateAlbum':
                return $user->canCreatePrivateAlbum();
            default:
                throw new \InvalidArgumentException("Privilège inconnu: $privilege");
        }
    }
}