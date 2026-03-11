<?php

require_once BASE_PATH . '/app/core/BaseModel.php';
// echo 'Connected db successfully!';

class IncidentModel extends BaseModel
{


    public function createIncident(array $data): int
    {
        $lat = trim($data['latitude'] ?? '');
        $lng = trim($data['longitude'] ?? '');

        $lat = $lat ===  '' ? null : (float) $lat;
        $lng = $lng === '' ? null : (float) $lng;

        $readable_address = $data['readable_address'] ?? 'Approximate location';

                
            $stmt = $this->pdo->prepare("
                INSERT INTO incidents 
                (
                    tracking_code, 
                    subject, 
                    date_of_incident, 
                    time_of_incident,
                    category, 
                    description, 
                    incident_type, 
                    location_building,
                    location_department, 
                    location_landmark,
                    readable_address, 
                    latitude, 
                    longitude
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
                        :location_landmark,
                        :readable_address,
                        :latitude,
                        :longitude
                        

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
                    ':readable_address' => $readable_address,
                    ':location_landmark' => $data['location_landmark'],
                    ':latitude'     => $lat,
                    ':longitude'    => $lng,
                    

                ]);

                return (int) $this->pdo->lastInsertId();  

    }




        public function updateReporterId(int $incidentId, int $reporterId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE incidents
            SET reporter_id = :reporter_id
            WHERE id = :id
        ");

        $stmt->execute([
            ':reporter_id' => $reporterId,
            ':id'          => $incidentId
        ]);
    }



      public function createEscalation(array $data): void
    {
        $sql = "
            INSERT INTO escalations
            (
                incident_id, 
                responder_id, 
                responder, 
                description, 
                escalated_by, 
                escalated_by_role)

            VALUES
            (   :incident_id, 
                :responder_id, 
                :responder, 
                :description, 
                :escalated_by, 
                :role)
        ";

        $this->pdo->prepare($sql)->execute([
            ':incident_id' => $data['incident_id'],
            ':responder_id'=> $data['responder_id'],
            ':responder'   => $data['responder_name'],
            ':description' => $data['description'],
            ':escalated_by'=> $data['user_id'],
            ':role'        => $data['role'],
        ]);
    }
   
    


    public function updateStatus(int $incidentId, string $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE incidents
            SET status = :status
            WHERE id = :id
        ");

        $stmt->execute([
            ':status' => $status,
            ':id'     => $incidentId
        ]);
    }




    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                i.*,
                a.priority
            FROM incidents i
            LEFT JOIN assessments a ON a.incident_id = i.id
            WHERE i.id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;


        // return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

  
    public function findByTrackingCode(string $code)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM incidents
            WHERE tracking_code = :code
            LIMIT 1
        ");
        $stmt->execute([':code' => $code]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;

        // return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    //     // Fetch single report by tracking code
    // public function getByTrackingCode(string $code)
    // {
    //     $stmt = $this->pdo->prepare("
    //         SELECT *
    //         FROM incidents
    //         WHERE tracking_code = :code
    //         LIMIT 1
    //     ");

    //     $stmt->execute([':code' => $code]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }




    protected $table = 'incidents';
    

    // public function getNewReports(array $filters = []): array
    // {
    //     $sql = "
    //         SELECT id, 
    //             tracking_code, 
    //             subject, 
    //             category, 
    //             incident_type, 
    //             status, 
    //             created_at
    //         FROM incidents
    //         WHERE status = 'new'
    //     ";
    //     $params = [];

        
    //     if (!empty($filters['category'])) {
    //         $sql .= " AND category = :category";
    //         $params['category'] = $filters['category'];
    //     }

        
        
    //     if (!empty($filters['status'])) {
    //         $sql .= " AND status = :status";
    //         $params['status'] = $filters['status'];
    //     }

    //     // Fatal incidents first
    //     $sql .= "
    //         ORDER BY CASE WHEN incident_type = 'fatal' THEN 1 ELSE 2 END, created_at DESC
    //     ";

    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute($params);

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }



    public function getNewReports(array $filters = []): array
{
    $params = [];
    $status = $filters['status'] ?? 'new';           // ← defaults to 'new' for staff

    if (!empty($filters['responder_id'])) {
        $sql = "
            SELECT i.id, 
                i.tracking_code, 
                i.subject, 
                i.category, 
                i.incident_type, 
                i.status, 
                i.created_at
            FROM incidents i
            INNER JOIN escalations e 
                ON e.incident_id = i.id 
                AND e.responder_id = :responder_id
            WHERE i.status = :status
        ";
        $params[':responder_id'] = $filters['responder_id'];
        $params[':status']       = $status;
    } else {
        $sql = "
            SELECT id, 
                tracking_code, 
                subject, 
                category, 
                incident_type, 
                status, 
                created_at
            FROM incidents
            WHERE status = :status
        ";
        $params[':status'] = $status;
    }

    if (!empty($filters['category'])) {
        $alias = !empty($filters['responder_id']) ? "i." : "";
        $sql  .= " AND {$alias}category = :category";
        $params[':category'] = $filters['category'];
    }

    $alias = !empty($filters['responder_id']) ? "i." : "";
    $sql  .= " ORDER BY CASE WHEN {$alias}incident_type = 'fatal' THEN 1 ELSE 2 END, {$alias}created_at DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    public function getReportsByStatus(string $status, ?array $filters = []): array
    {
        // Base query

        $sql = "
            SELECT i.*, 
                a.validity, 
                a.priority, 
                a.remarks
            FROM incidents i
            LEFT JOIN assessments a ON a.incident_id = i.id
            WHERE i.status = :status
        ";

        // Parameters array for prepared statement
        $params = [':status' => $status];

        // Optional filters
        if (!empty($filters['validity'])) {
            $sql .= " AND a.validity = :validity";
            $params[':validity'] = $filters['validity'];
        }

        if (!empty($filters['priority']) && is_array($filters['priority'])) {
            // Prepare placeholders for IN clause
            $placeholders = [];
            foreach ($filters['priority'] as $index => $priority) {
                $key = ":priority$index";
                $placeholders[] = $key;
                $params[$key] = $priority;
            }
            $sql .= " AND a.priority IN (" . implode(',', $placeholders) . ")";
        }

        // Order by newest first
        $sql .= " ORDER BY i.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getAllReports(?string $status = null)
    {
        if ($status) {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM incidents WHERE status = :status ORDER BY created_at DESC"
            );
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query(
                "SELECT * FROM incidents ORDER BY created_at DESC"
            );
        }

        return $stmt->fetchAll();
    }




    public function findIncidentSummaryByTrackingCode(string $trackingCode)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                i.id,
                i.tracking_code,
                i.status,
                i.created_at,

                ia.action_taken,
                ia.status_update,
                ia.created_at AS action_date,

                u.username AS responder_name,
                u.role AS responder_role

            FROM incidents i
            LEFT JOIN incident_actions ia 
                ON ia.incident_id = i.id
            LEFT JOIN users u 
                ON u.id = ia.responder_id

            WHERE i.tracking_code = :code
            ORDER BY ia.created_at DESC
            LIMIT 1
        ");

        $stmt->execute([':code' => $trackingCode]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;


        // return $stmt->fetch(PDO::FETCH_ASSOC);
    }





    public function isIncidentAssignedToResponder(int $incidentId, int $responderId): bool
    {
        // var_dump($incident['id'], $responderId);
        
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM escalations
            WHERE incident_id = :incident_id
            AND responder_id = :responder_id
            LIMIT 1
            ");
        $stmt->execute([
            ':incident_id' => $incidentId,
            ':responder_id' => $responderId
            ]);


        return (bool) $stmt->fetchColumn();
    }




    public function getAssignedIncidents(int $responderId, ?string $status = null): array
    {
        $sql = "
        SELECT i.*
        FROM incidents i
        INNER JOIN escalations e ON e.incident_id = i.id
        WHERE e.responder_id = :responder_id
        ";
        if ($status !== null) {
        $sql .= " AND i.status = :status";
        }
        $sql .= " ORDER BY e.escalated_at DESC";


        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':responder_id', $responderId, PDO::PARAM_INT);
        if ($status !== null) $stmt->bindValue(':status', $status);


        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function updateStatusByTrackingCode(string $trackingCode, string $newStatus)
    {
        $stmt = $this->pdo->prepare("
        UPDATE incidents
        SET status = :status
        WHERE tracking_code = :tracking_code
        ");
        $stmt->execute([
        ':status' => $newStatus,
        ':tracking_code' => $trackingCode
        ]);
    }


public function addAction(array $data): void
{
    $stmt = $this->pdo->prepare("
        INSERT INTO incident_actions
            (incident_id, responder_id, action_taken, resolution_date, resolution_time,
             status_update, investigation_findings, resolution_disposition,
             report_officer_first, report_officer_middle, report_officer_last, report_officer_position,
             signatory_first, signatory_middle, signatory_last, signatory_position,
             created_at)
        VALUES
            (:incident_id, :responder_id, :action_taken, :resolution_date, :resolution_time,
             :status_update, :investigation_findings, :resolution_disposition,
             :report_officer_first, :report_officer_middle, :report_officer_last, :report_officer_position,
             :signatory_first, :signatory_middle, :signatory_last, :signatory_position,
             NOW())
    ");

    $stmt->execute([
        ':incident_id'             => $data['incident_id'],
        ':responder_id'            => $data['acted_by'],
        ':action_taken'            => $data['action_taken'],
        ':resolution_date'         => $data['resolution_date']         ?? date('Y-m-d'),
        ':resolution_time'         => $data['resolution_time']         ?? date('H:i:s'),
        ':status_update'           => $data['status'],
        ':investigation_findings'  => $data['investigation_findings']  ?? null,
        ':resolution_disposition'  => $data['resolution_disposition']  ?? null,
        ':report_officer_first'    => $data['report_officer_first']    ?? null,
        ':report_officer_middle'   => $data['report_officer_middle']   ?? null,
        ':report_officer_last'     => $data['report_officer_last']     ?? null,
        ':report_officer_position' => $data['report_officer_position'] ?? null,
        ':signatory_first'         => $data['signatory_first']         ?? null,
        ':signatory_middle'        => $data['signatory_middle']        ?? null,
        ':signatory_last'          => $data['signatory_last']          ?? null,
        ':signatory_position'      => $data['signatory_position']      ?? null,
    ]);

    $actionId = (int) $this->pdo->lastInsertId();

    if (!empty($data['involved_parties'])) {
        $partyStmt = $this->pdo->prepare("
            INSERT INTO incident_involved_parties
            (incident_action_id, owner_of_property, affected_area, description)
            VALUES (:action_id, :owner, :area, :description)
        ");
        foreach ($data['involved_parties'] as $party) {
            $partyStmt->execute([
                ':action_id'   => $actionId,
                ':owner'       => $party['owner']       ?? '',
                ':area'        => $party['area']        ?? '',
                ':description' => $party['description'] ?? '',
            ]);
        }
    }
}


public function beginTransaction(): void
{
    $this->pdo->beginTransaction();
}

public function commit(): void
{
    $this->pdo->commit();
}

public function rollBack(): void
{
    $this->pdo->rollBack();
}



//    public function getIncidentActions(int $incidentId): array
// {
//     $stmt = $this->pdo->prepare("
//         SELECT a.id, a.responder_id, u.username AS responder_name,
//                a.action_taken, a.resolution_date, a.resolution_time,
//                a.status_update, a.investigation_findings, a.resolution_disposition,
//                a.created_at,
//                a.report_officer_first, a.report_officer_middle, a.report_officer_last,
//                 a.report_officer_position,
//                 a.signatory_first, a.signatory_middle, a.signatory_last,
//                 a.signatory_position
               
//         FROM incident_actions a
//         JOIN users u ON a.responder_id = u.id
//         WHERE a.incident_id = :incident_id
//         ORDER BY a.created_at ASC
//     ");
//     $stmt->execute([':incident_id' => $incidentId]);
//     $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Attach involved parties to each action
//     $partyStmt = $this->pdo->prepare("
//         SELECT owner_of_property AS owner, affected_area AS area, description
//         FROM incident_involved_parties
//         WHERE incident_action_id = :action_id
//     ");

//     foreach ($actions as &$action) {
//         $partyStmt->execute([':action_id' => $action['id']]);
//         $action['involved_parties'] = $partyStmt->fetchAll(PDO::FETCH_ASSOC);
//     }

//     return $actions;
// }


public function getIncidentActions(int $incidentId): array
{
   $stmt = $this->pdo->prepare("
    SELECT a.id, a.acted_by,
           u.username AS actor_username,
           u.role     AS actor_role,
           a.action_taken, a.resolution_date, a.resolution_time,
           a.status_update, a.investigation_findings, a.resolution_disposition,
           a.created_at,
           a.report_officer_first, a.report_officer_middle, a.report_officer_last,
           a.report_officer_position,
           a.signatory_first, a.signatory_middle, a.signatory_last,
           a.signatory_position
    FROM incident_actions a
    LEFT JOIN users u ON u.id = a.acted_by
    WHERE a.incident_id = :incident_id
    ORDER BY a.created_at ASC
");
    $stmt->execute([':incident_id' => $incidentId]);
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $partyStmt = $this->pdo->prepare("
        SELECT owner_of_property AS owner, affected_area AS area, description
        FROM incident_involved_parties
        WHERE incident_action_id = :action_id
    ");

    foreach ($actions as &$action) {
        $partyStmt->execute([':action_id' => $action['id']]);
        $action['involved_parties'] = $partyStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $actions;
}




   public function getFilteredIncidents(string $category = '', string $status = '', string $dateFrom = '', string $dateTo = ''): array
{
    $query = "
        SELECT 
            i.*,
            CONCAT_WS(', ', i.location_building, i.location_department, i.location_landmark) AS location,
            COALESCE(u.username, 'Anonymous') AS reporter_name
        FROM incidents i
        LEFT JOIN reporters r ON r.id = i.reporter_id
        LEFT JOIN users u ON u.id = r.user_id
        WHERE 1=1
    ";

    $params = [];

    if ($category) {
        $query .= " AND i.category = :category";
        $params[':category'] = $category;
    }

    if ($status) {
        $query .= " AND i.status = :status";
        $params[':status'] = $status;
    }


    if ($dateFrom) {
        $query .= " AND DATE(i.date_of_incident) >= :date_from";
        $params[':date_from'] = $dateFrom;
    }

    if ($dateTo) {
        $query .= " AND DATE(i.date_of_incident) <= :date_to";
        $params[':date_to'] = $dateTo;
    }

    
    $query .= " ORDER BY i.created_at DESC";

    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





   public function escalateIncident(array $data): void
{
    $this->pdo->beginTransaction();

    try {
        // Check if already escalated
        $stmt = $this->pdo->prepare("
            SELECT 1 FROM escalations WHERE incident_id = :incident_id LIMIT 1
        ");
        $stmt->execute([':incident_id' => $data['incident_id']]);

        if ($stmt->fetchColumn()) {
            throw new RuntimeException('Incident already escalated');
        }

        // Insert escalation
        $stmt = $this->pdo->prepare("
            INSERT INTO escalations
                (incident_id, responder_id, responder, description, escalated_by)
            VALUES
                (:incident_id, :responder_id, :responder, :description, :staff_id)
        ");
        $stmt->execute([
            ':incident_id'  => $data['incident_id'],
            ':responder_id' => $data['responder_id'],
            ':responder'    => $data['responder_name'],
            ':description'  => $data['description'],
            ':staff_id'     => $data['staff_id'],
        ]);

        // Update incident status
        $stmt = $this->pdo->prepare("
            UPDATE incidents SET status = 'escalated' WHERE id = :id
        ");
        $stmt->execute([':id' => $data['incident_id']]);

        $this->pdo->commit();

    } catch (\Throwable $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}



public function getInvolvedParties(int $actionId): array
{
    $stmt = $this->pdo->prepare("
        SELECT owner_of_property, affected_area, description
        FROM incident_involved_parties
        WHERE incident_action_id = :action_id
    ");
    $stmt->execute([':action_id' => $actionId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






}

?>