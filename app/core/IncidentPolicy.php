<?php

class IncidentPolicy
{
    // public static function staffCanTakeAction(array $incident, array $user): bool
    // {
    //     return $user['role'] === 'staff'
    //         && strtolower($incident['status']) === 'validated';
    // }

    // public static function responderCanTakeAction(array $incident, array $user): bool
    // {
    //     return $user['role'] === 'responder'
    //         && in_array(strtolower($incident['status']), ['validated', 'ongoing', 'escalated'], true);
    // }

    // public static function canViewActions(array $incident, array $user): bool
    // {
    //     return in_array(strtolower($incident['status']), ['resolved', 'escalated'], true);
    // }


    


    //  public static function staffCanTakeAction(array $incident, array $user): bool
    // {
    //     return $user['role'] === 'staff'
    //         && strtolower($incident['status']) === 'validated';
    // }


        public static function staffCanTakeAction(array $incident, array $user): bool
    {
        return in_array($incident['status'], ['validated', 'ongoing'], true);
    }


    public static function responderCanTakeAction(array $incident, array $user): bool
    {
        return $user['role'] === 'responder'
            && in_array(strtolower($incident['status']), [
                'ongoing',
                'escalated'
            ], true);
    }

    public static function canViewActions(array $incident, array $user): bool
    {
        // Staff, responders, and admins can view actions once resolved/escalated
        return in_array($user['role'], ['staff', 'responder', 'admin'], true)
            && in_array(strtolower($incident['status']), ['resolved', 'escalated'], true);
    }

    public static function isFinalStatus(string $status): bool
    {
        return in_array(strtolower($status), [
            'invalidated',
            'resolved'
        ], true);
    }


    public static function canEscalate(array $incident): bool
    {
        return strtolower($incident['status']) === 'validated';
    }
    
}