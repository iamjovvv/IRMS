<?php

require_once BASE_PATH . '/app/core/BaseModel.php';
// echo 'Connected db successfully!';

class IncidentModel extends BaseModel
{


    public function createIncident(array $data): int
    {
       $stmt = $this->pdo->prepare("
        INSERT INTO incidents 
        (
            tracking_code, 
            subject, date_of_incident, time_of_incident,
            category, 
            description, incident_type, location_building,
            location_department, location_landmark
            )

            VALUES
            (
                :tracking_code, 
                :subject, 
                :date, 
                :time, 
                :category, 
                :description, 
                :type,
                :location_building,
                :location_department,
                :location_landmark

            )
       ");

        

        $stmt->execute([
            ':tracking_code' => $data['tracking_code'],
            ':subject' => $data['subject'],
            ':date' => $data['date_of_incident'],
            ':time' => $data['time_of_incident'],
            ':category' => $data['category'],
            ':description' => $data['description'],
            ':type' => $data['incident_type'],
            ':location_building' => $data['location_building'],
            ':location_department' => $data['location_department'],
            ':location_landmark' => $data['location_landmark']

        ]);

        return (int) $this->pdo->lastInsertId();
    

    }



   

    public function findByTrackingCode(string $code)
{
   

    $stmt = $this->pdo->prepare("
        SELECT * FROM incidents
        WHERE tracking_code = :code
        LIMIT 1
    ");
    $stmt->execute([':code' => $code]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}




    
  
 // Fetch only NEW reports
    // public function getNewReports()
    // {
    //     $stmt = $this->pdo->prepare("
    //         SELECT 
    //             id,
    //             tracking_code,
    //             subject,
    //             category,
    //             priority,
    //             status,
    //             created_at
    //         FROM incidents
    //         WHERE status = 'new'
    //         ORDER BY created_at DESC
    //     ");

    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }


//   public function getNewReports(array $filters = []): array
// {
//     $sql = "
//         SELECT id, tracking_code, subject, category, incident_type, status, created_at
//         FROM incidents
//         WHERE status = 'submitted'
//     ";

//     $params = [];

//     // optional category filter
//     if (!empty($filters['category'])) {
//         $sql .= " AND category = :category";
//         $params['category'] = $filters['category'];
//     }

//     // Fatal reports first, newest first
//     $sql .= "
//         ORDER BY CASE WHEN incident_type = 'fatal' THEN 1 ELSE 2 END, created_at DESC
//     ";

//     $stmt = $this->pdo->prepare($sql);
//     $stmt->execute($params);

//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }


public function getNewReports(array $filters = []): array
{
    $sql = "
        SELECT id, tracking_code, subject, category, incident_type, status, created_at
        FROM incidents
        WHERE 1
    ";
    $params = [];

    // Filter category
    if (!empty($filters['category'])) {
        $sql .= " AND category = :category";
        $params['category'] = $filters['category'];
    }

    // Filter status
    if (!empty($filters['status'])) {
        $sql .= " AND status = :status";
        $params['status'] = $filters['status'];
    }

    // Fatal incidents first
    $sql .= "
        ORDER BY CASE WHEN incident_type = 'fatal' THEN 1 ELSE 2 END, created_at DESC
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





    // Fetch single report by tracking code
    public function getByTrackingCode(string $code)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM incidents
            WHERE tracking_code = :code
            LIMIT 1
        ");

        $stmt->execute([':code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



}

?>