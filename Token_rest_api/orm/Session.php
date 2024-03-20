<?php

class Session {
    
    public function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function setSessionUser($userId) {
        $_SESSION['id'] = $userId;
    }
    
    public function getSessionUser($key) {
        $this->start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    
    public function removeSessionUser($key) {
        $this->start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public function destroySession() {
        $this->start();
        session_destroy();
    }
    

    public function createSession($userToken, $userID, $tokenExpire) {
        $sql = "INSERT INTO session (UserToken, UserID, TokenExpire) VALUES (:userToken, :userID, :tokenExpire)";
        
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userToken', $userToken);
            $stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':tokenExpire', $tokenExpire);
            
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur d'insertion dans la table de session: " . $e->getMessage();
        }
    }

    public function getTokenByUserId($userId) {
        $database = new Database();
        $conn = $database->getConnection();
    
        $query = "SELECT userToken FROM session WHERE userId = :userId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return ($result && isset($result['userToken'])) ? $result['userToken'] : null;
    }
    
    
}

?>
