<?php

require_once BASE_PATH . '/app/core/BaseModel.php';



class IncidentMediaModel extends BaseModel
{

   

    public function create($data) {
       
        $stmt = $this->pdo->prepare("
            INSERT INTO incident_media 
            (incident_id, file_path, file_type, uploaded_by)
            VALUES (:incident_id, :file_path, :file_type, :uploaded_by)
        ");

        return $stmt->execute($data);
    }



      public function getByIncidentId($incidentId)
    {
       

        $stmt = $this->pdo->prepare("
            SELECT * FROM incident_media
            WHERE incident_id = :id
        ");
        $stmt->execute(['id' => $incidentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function delete($mediaId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM incident_media WHERE id= :id");
        return $stmt->execute(['id' => $mediaId]);
    }




     

}


?>